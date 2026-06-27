<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Package;
use App\Models\PsychologyQuestion;
use App\Models\Announcement;
use App\Models\TestSession;
use App\Models\Student;
use App\Models\TestResult;
use App\Models\User;
use App\Models\Violation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()?->role === 'owner') {
            return $this->ownerDashboard();
        }

        $platformAdminId = User::where('role', 'admin')->orderBy('id')->value('id');
        $canMonitorOwners = auth()->user()->role === 'admin' && auth()->id() === $platformAdminId;
        $ownerMonitoring = collect();
        $systemStats = [
            'total_owners' => 0,
            'active_owners' => 0,
            'total_records' => 0,
            'total_activities' => 0,
        ];

        if ($canMonitorOwners) {
            $owners = User::where('role', 'owner')
                ->orderByDesc('created_at')
                ->get();

            $ownerIds = $owners->pluck('id');

            $studentCounts = Student::withoutGlobalScopes()
                ->select('owner_id')
                ->selectRaw('COUNT(*) as total')
                ->whereIn('owner_id', $ownerIds)
                ->groupBy('owner_id')
                ->pluck('total', 'owner_id');

            $packageCounts = Package::withoutGlobalScopes()
                ->select('owner_id')
                ->selectRaw('COUNT(*) as total')
                ->whereIn('owner_id', $ownerIds)
                ->groupBy('owner_id')
                ->pluck('total', 'owner_id');

            $sessionCounts = TestSession::withoutGlobalScopes()
                ->select('owner_id')
                ->selectRaw('COUNT(*) as total')
                ->whereIn('owner_id', $ownerIds)
                ->groupBy('owner_id')
                ->pluck('total', 'owner_id');

            $questionCounts = PsychologyQuestion::withoutGlobalScopes()
                ->select('owner_id')
                ->selectRaw('COUNT(*) as total')
                ->whereIn('owner_id', $ownerIds)
                ->groupBy('owner_id')
                ->pluck('total', 'owner_id');

            $announcementCounts = Announcement::withoutGlobalScopes()
                ->select('owner_id')
                ->selectRaw('COUNT(*) as total')
                ->whereIn('owner_id', $ownerIds)
                ->groupBy('owner_id')
                ->pluck('total', 'owner_id');

            $resultCounts = TestResult::withoutGlobalScopes()
                ->select('owner_id')
                ->selectRaw('COUNT(*) as total')
                ->whereIn('owner_id', $ownerIds)
                ->groupBy('owner_id')
                ->pluck('total', 'owner_id');

            $violationCounts = Violation::withoutGlobalScopes()
                ->select('owner_id')
                ->selectRaw('COUNT(*) as total')
                ->whereIn('owner_id', $ownerIds)
                ->groupBy('owner_id')
                ->pluck('total', 'owner_id');

            $activityCounts = ActivityLog::withoutGlobalScopes()
                ->select('owner_id')
                ->selectRaw('COUNT(*) as total')
                ->whereIn('owner_id', $ownerIds)
                ->groupBy('owner_id')
                ->pluck('total', 'owner_id');

            $latestActivities = ActivityLog::withoutGlobalScopes()
                ->with('user')
                ->whereIn('owner_id', $ownerIds)
                ->latest()
                ->get()
                ->groupBy('owner_id');

            $ownerMonitoring = $owners->map(function (User $owner) use ($studentCounts, $packageCounts, $sessionCounts, $questionCounts, $announcementCounts, $resultCounts, $violationCounts, $activityCounts, $latestActivities) {
                $lastActivity = $latestActivities->get($owner->id)?->first();
                $counts = [
                    'students' => (int) ($studentCounts[$owner->id] ?? 0),
                    'packages' => (int) ($packageCounts[$owner->id] ?? 0),
                    'sessions' => (int) ($sessionCounts[$owner->id] ?? 0),
                    'questions' => (int) ($questionCounts[$owner->id] ?? 0),
                    'announcements' => (int) ($announcementCounts[$owner->id] ?? 0),
                    'results' => (int) ($resultCounts[$owner->id] ?? 0),
                    'violations' => (int) ($violationCounts[$owner->id] ?? 0),
                    'activities' => (int) ($activityCounts[$owner->id] ?? 0),
                ];

                return [
                    'owner' => $owner,
                    'counts' => $counts,
                    'total_records' => array_sum($counts),
                    'last_activity' => $lastActivity ? $this->formatActivityLabel($lastActivity) : '-',
                    'last_activity_at' => $lastActivity?->created_at,
                ];
            });

            $systemStats = [
                'total_owners' => $owners->count(),
                'active_owners' => $owners->where('is_active', true)->count(),
                'total_records' => $ownerMonitoring->sum('total_records'),
                'total_activities' => $activityCounts->sum(),
            ];
        }

        return view('admin.dashboard.index', compact(
            'ownerMonitoring',
            'systemStats',
            'canMonitorOwners'
        ));
    }

    private function ownerDashboard()
    {
        $totalStudents = Student::count();
        $completedStudents = Student::where('status', 'completed')->count();
        $totalViolations = Violation::count();
        $totalPackages = Package::count();
        $activeSessions = TestSession::where('is_active', true)->count();
        $totalQuestions = PsychologyQuestion::count();
        $totalResults = TestResult::count();
        $totalAnnouncements = Announcement::count();

        $packagePreferences = Package::withCount([
            'firstChoices',
            'secondChoices',
        ])->get();

        $recommendationDistribution = Package::withCount('testResultsRecommended')
            ->pluck('test_results_recommended_count', 'name')
            ->all();

        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->limit(6)
            ->get()
            ->map(function (ActivityLog $log) {
                $log->display_label = $this->formatActivityLabel($log);

                return $log;
            });

        $upcomingSessions = TestSession::where('is_active', true)
            ->orderBy('test_date')
            ->orderBy('start_time')
            ->limit(4)
            ->get();

        return view('admin.dashboard.owner', compact(
            'totalStudents',
            'completedStudents',
            'totalViolations',
            'totalPackages',
            'activeSessions',
            'totalQuestions',
            'totalResults',
            'totalAnnouncements',
            'packagePreferences',
            'recommendationDistribution',
            'recentActivities',
            'upcomingSessions'
        ));
    }

    public function resetOwnerData(Request $request)
    {
        abort_unless(auth()->user()->role === 'owner', 403);

        $request->validate([
            'confirmation' => ['required', 'in:RESET'],
        ]);

        $ownerId = auth()->id();

        DB::transaction(function () use ($ownerId) {
            // Hapus data yang bergantung pada students
            $studentIds = Student::withoutGlobalScopes()
                ->where('owner_id', $ownerId)
                ->pluck('id');

            if ($studentIds->isNotEmpty()) {
                DB::table('student_test_sessions')->whereIn('student_id', $studentIds)->delete();
                DB::table('student_psychology_answers')->whereIn('student_id', $studentIds)->delete();
                DB::table('student_academic_answers')->whereIn('student_id', $studentIds)->delete();
                DB::table('student_package_choices')->whereIn('student_id', $studentIds)->delete();
                DB::table('student_selfies')->whereIn('student_id', $studentIds)->delete();
                DB::table('student_biodatas')->whereIn('student_id', $studentIds)->delete();
                DB::table('class_students')->whereIn('student_id', $studentIds)->delete();
                DB::table('announcement_responses')->whereIn('student_id', $studentIds)->delete();
                DB::table('objections')->whereIn('student_id', $studentIds)->delete();
            }

            // Hapus data berdasarkan owner_id
            DB::table('violations')->where('owner_id', $ownerId)->delete();
            DB::table('test_results')->where('owner_id', $ownerId)->delete();
            DB::table('class_groups')->where('owner_id', $ownerId)->delete();
            DB::table('announcements')->where('owner_id', $ownerId)->delete();
            DB::table('test_session_classes')->whereIn('test_session_id',
                DB::table('test_sessions')->where('owner_id', $ownerId)->pluck('id')
            )->delete();
            DB::table('test_sessions')->where('owner_id', $ownerId)->delete();
            DB::table('activity_logs')->where('owner_id', $ownerId)->delete();

            // Hapus students dan users siswa
            $userIds = Student::withoutGlobalScopes()
                ->where('owner_id', $ownerId)
                ->pluck('user_id')
                ->filter();

            Student::withoutGlobalScopes()->where('owner_id', $ownerId)->delete();

            if ($userIds->isNotEmpty()) {
                User::whereIn('id', $userIds)->delete();
            }

            // Hapus soal instrumen peminatan beserta opsi dan bobot
            $questionIds = DB::table('psychology_questions')->where('owner_id', $ownerId)->pluck('id');
            if ($questionIds->isNotEmpty()) {
                $optionIds = DB::table('psychology_question_options')->whereIn('question_id', $questionIds)->pluck('id');
                if ($optionIds->isNotEmpty()) {
                    DB::table('psychology_option_weights')->whereIn('option_id', $optionIds)->delete();
                }
                DB::table('psychology_question_options')->whereIn('question_id', $questionIds)->delete();
            }
            DB::table('psychology_questions')->where('owner_id', $ownerId)->delete();

            // Hapus jurusan beserta mata pelajaran
            $packageIds = DB::table('packages')->where('owner_id', $ownerId)->pluck('id');
            if ($packageIds->isNotEmpty()) {
                DB::table('package_subjects')->whereIn('package_id', $packageIds)->delete();
            }
            DB::table('packages')->where('owner_id', $ownerId)->delete();
        });

        // Hapus file selfie dan gambar soal milik owner ini
        Storage::disk('public')->deleteDirectory("selfies/{$ownerId}");
        Storage::disk('public')->deleteDirectory("question-images");

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Semua data berhasil direset sepenuhnya.');
    }

    public function resetData(Request $request)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

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

    private function formatActivityLabel(ActivityLog $log): string
    {
        $module = Str::headline(str_replace('_', ' ', (string) $log->module));
        $action = match ($log->action) {
            'create' => 'membuat',
            'update' => 'memperbarui',
            'delete' => 'menghapus',
            'import' => 'mengimpor',
            'publish' => 'mempublikasikan',
            'lock_final' => 'mengunci final',
            'auto_distribute' => 'mendistribusikan otomatis',
            'manual_move' => 'memindahkan manual',
            'create_class_group' => 'membuat kelas',
            'update_class_group' => 'memperbarui kelas',
            default => Str::headline(str_replace('_', ' ', (string) $log->action)),
        };

        return trim($action . ' ' . $module);
    }
}
