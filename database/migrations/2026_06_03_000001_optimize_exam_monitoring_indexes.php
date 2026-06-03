<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_test_sessions', function (Blueprint $table) {
            $table->index(
                ['academic_started_at', 'academic_submitted_at', 'test_session_id'],
                'sts_academic_monitoring_index'
            );

            $table->index(
                ['psychology_started_at', 'psychology_submitted_at', 'test_session_id'],
                'sts_psychology_monitoring_index'
            );
        });

        Schema::table('violations', function (Blueprint $table) {
            $table->index(
                ['test_session_id', 'student_id', 'exam_type', 'occurred_at'],
                'violations_monitoring_lookup_index'
            );
        });
    }

    public function down(): void
    {
        Schema::table('violations', function (Blueprint $table) {
            $table->dropIndex('violations_monitoring_lookup_index');
        });

        Schema::table('student_test_sessions', function (Blueprint $table) {
            $table->dropIndex('sts_psychology_monitoring_index');
            $table->dropIndex('sts_academic_monitoring_index');
        });
    }
};
