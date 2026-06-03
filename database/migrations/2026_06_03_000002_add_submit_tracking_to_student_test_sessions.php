<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_test_sessions', function (Blueprint $table) {
            $table->unsignedInteger('academic_duration_seconds')->nullable()->after('academic_submitted_at');
            $table->string('academic_submit_type', 20)->nullable()->after('academic_duration_seconds');
            $table->unsignedInteger('psychology_duration_seconds')->nullable()->after('psychology_submitted_at');
            $table->string('psychology_submit_type', 20)->nullable()->after('psychology_duration_seconds');

            $table->index('academic_submitted_at', 'sts_academic_submitted_at_index');
            $table->index('psychology_submitted_at', 'sts_psychology_submitted_at_index');
        });
    }

    public function down(): void
    {
        Schema::table('student_test_sessions', function (Blueprint $table) {
            $table->dropIndex('sts_psychology_submitted_at_index');
            $table->dropIndex('sts_academic_submitted_at_index');

            $table->dropColumn([
                'academic_duration_seconds',
                'academic_submit_type',
                'psychology_duration_seconds',
                'psychology_submit_type',
            ]);
        });
    }
};
