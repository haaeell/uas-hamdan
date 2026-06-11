<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Student;
use App\Models\TestResult;
use App\Models\User;
use App\Models\Violation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        $totalStudents = Student::count();
        $completedStudents = Student::where('status', 'completed')->count();
        $totalViolations = Violation::count();

        $packagePreferences = Package::withCount([
            'firstChoices',
            'secondChoices',
        ])
            ->get();

        $recommendationDistribution = Package::withCount('testResultsRecommended')
            ->pluck('test_results_recommended_count', 'name')
            ->all();

        return view('admin.dashboard.index', compact(
            'totalStudents',
            'completedStudents',
            'totalViolations',
            'packagePreferences',
            'recommendationDistribution'
        ));
    }

    public function resetData(Request $request)
    {
        $request->validate([
            'confirmation' => ['required', 'in:RESET'],
        ]);

        $tables = [
            'activity_logs',
            'violations',
            'objections',
            'announcement_responses',
            'announcements',
            'class_students',
            'class_groups',
            'test_results',
            'student_psychology_answers',
            'psychology_option_weights',
            'psychology_question_options',
            'psychology_questions',
            'student_academic_answers',
            'academic_question_options',
            'academic_questions',
            'student_package_choices',
            'package_subjects',
            'packages',
            'student_test_sessions',
            'test_session_classes',
            'test_sessions',
            'student_selfies',
            'student_biodatas',
            'students',
        ];

        Schema::disableForeignKeyConstraints();

        try {
            foreach ($tables as $table) {
                if (Schema::hasTable($table)) {
                    DB::table($table)->truncate();
                }
            }

            User::where('role', '!=', 'admin')->delete();
        } finally {
            Schema::enableForeignKeyConstraints();
        }

        foreach (['selfies', 'question-images', 'surat'] as $directory) {
            Storage::disk('public')->deleteDirectory($directory);
        }

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Semua data berhasil direset. Akun admin dan pengaturan tetap dipertahankan.');
    }
}
