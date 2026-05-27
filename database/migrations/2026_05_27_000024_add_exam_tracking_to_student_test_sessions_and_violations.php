<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_test_sessions', function (Blueprint $table) {
            $table->timestamp('academic_started_at')->nullable()->after('started_at');
            $table->timestamp('psychology_started_at')->nullable()->after('academic_started_at');
            $table->timestamp('academic_submitted_at')->nullable()->after('psychology_started_at');
            $table->timestamp('psychology_submitted_at')->nullable()->after('academic_submitted_at');
            $table->unsignedInteger('academic_violation_count')->default(0)->after('psychology_submitted_at');
            $table->unsignedInteger('psychology_violation_count')->default(0)->after('academic_violation_count');
        });

        Schema::table('violations', function (Blueprint $table) {
            $table->foreignId('test_session_id')
                ->nullable()
                ->after('student_id')
                ->constrained('test_sessions')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('violations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('test_session_id');
        });

        Schema::table('student_test_sessions', function (Blueprint $table) {
            $table->dropColumn([
                'academic_started_at',
                'psychology_started_at',
                'academic_submitted_at',
                'psychology_submitted_at',
                'academic_violation_count',
                'psychology_violation_count',
            ]);
        });
    }
};
