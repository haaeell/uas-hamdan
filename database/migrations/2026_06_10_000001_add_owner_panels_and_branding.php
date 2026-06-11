<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('owner_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            $table->string('exam_token', 64)->nullable()->unique()->after('remember_token');
        });

        foreach ([
            'students',
            'student_biodatas',
            'student_package_choices',
            'packages',
            'test_sessions',
            'psychology_questions',
            'student_psychology_answers',
            'test_results',
            'announcements',
            'announcement_responses',
            'objections',
            'violations',
            'class_groups',
            'class_students',
            'activity_logs',
        ] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->foreignId('owner_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            });
        }

        Schema::table('packages', function (Blueprint $table) {
            $table->dropUnique(['code']);
            $table->unique(['owner_id', 'code']);
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->foreignId('owner_id')->nullable()->after('id')->constrained('users')->cascadeOnDelete();
            $table->dropUnique(['key']);
            $table->unique(['owner_id', 'key']);
        });

        $ownerId = DB::table('users')->where('role', 'admin')->orderBy('id')->value('id');

        if ($ownerId) {
            DB::table('users')->where('role', 'admin')->whereNull('owner_id')->orderBy('id')->pluck('id')->each(function ($adminId) {
                DB::table('users')->where('id', $adminId)->update([
                    'owner_id' => $adminId,
                    'exam_token' => Str::random(32),
                ]);
            });

            DB::table('users')->where('role', 'siswa')->whereNull('owner_id')->update(['owner_id' => $ownerId]);

            foreach (['students', 'packages', 'test_sessions', 'psychology_questions', 'announcements', 'class_groups', 'activity_logs'] as $tableName) {
                DB::table($tableName)->whereNull('owner_id')->update(['owner_id' => $ownerId]);
            }

            foreach ([
                'student_biodatas',
                'student_package_choices',
                'student_psychology_answers',
                'test_results',
                'announcement_responses',
                'objections',
                'violations',
                'class_students',
            ] as $tableName) {
                DB::table($tableName)
                    ->join('students', $tableName . '.student_id', '=', 'students.id')
                    ->whereNull($tableName . '.owner_id')
                    ->update([$tableName . '.owner_id' => DB::raw('students.owner_id')]);
            }

            DB::table('settings')->whereNull('owner_id')->update(['owner_id' => $ownerId]);
        }
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropUnique(['owner_id', 'key']);
            $table->unique('key');
            $table->dropConstrainedForeignId('owner_id');
        });

        Schema::table('packages', function (Blueprint $table) {
            $table->dropUnique(['owner_id', 'code']);
            $table->unique('code');
        });

        foreach ([
            'activity_logs',
            'class_students',
            'class_groups',
            'violations',
            'objections',
            'announcement_responses',
            'announcements',
            'test_results',
            'student_psychology_answers',
            'psychology_questions',
            'test_sessions',
            'packages',
            'student_package_choices',
            'student_biodatas',
            'students',
        ] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropConstrainedForeignId('owner_id');
            });
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['exam_token']);
            $table->dropColumn('exam_token');
            $table->dropConstrainedForeignId('owner_id');
        });
    }
};
