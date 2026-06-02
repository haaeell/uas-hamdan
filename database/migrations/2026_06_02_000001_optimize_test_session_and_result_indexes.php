<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('test_sessions', function (Blueprint $table) {
            $table->index(
                ['is_active', 'test_date', 'start_time', 'end_time'],
                'test_sessions_active_window_index'
            );
        });

        Schema::table('test_session_classes', function (Blueprint $table) {
            $table->index(
                ['origin_class', 'test_session_id'],
                'test_session_classes_origin_session_index'
            );
        });

        Schema::table('student_test_sessions', function (Blueprint $table) {
            $table->index(
                ['test_session_id', 'student_id'],
                'student_test_sessions_session_student_index'
            );
        });

        Schema::table('test_results', function (Blueprint $table) {
            $table->unique('student_id', 'test_results_student_unique');
        });

        Schema::table('violations', function (Blueprint $table) {
            $table->index(
                ['test_session_id', 'student_id', 'occurred_at'],
                'violations_session_student_occurred_index'
            );
        });
    }

    public function down(): void
    {
        Schema::table('violations', function (Blueprint $table) {
            $table->dropIndex('violations_session_student_occurred_index');
        });

        Schema::table('test_results', function (Blueprint $table) {
            $table->dropUnique('test_results_student_unique');
        });

        Schema::table('student_test_sessions', function (Blueprint $table) {
            $table->dropIndex('student_test_sessions_session_student_index');
        });

        Schema::table('test_session_classes', function (Blueprint $table) {
            $table->dropIndex('test_session_classes_origin_session_index');
        });

        Schema::table('test_sessions', function (Blueprint $table) {
            $table->dropIndex('test_sessions_active_window_index');
        });
    }
};
