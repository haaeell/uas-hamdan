<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OwnerStatusMail;
use App\Models\ActivityLog;
use App\Models\Announcement;
use App\Models\Package;
use App\Models\PsychologyQuestion;
use App\Models\Student;
use App\Models\TestResult;
use App\Models\TestSession;
use App\Models\User;
use App\Models\Violation;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class OwnerApprovalController extends Controller
{
    public function index(): View
    {
        $owners = User::where('role', 'owner')
            ->latest()
            ->get()
            ->map(fn (User $owner) => $this->ownerCard($owner));

        return view('admin.owner-approvals.index', [
            'pendingOwners' => $owners->where('status', 'pending')->values(),
            'approvedOwners' => $owners->where('status', 'approved')->values(),
        ]);
    }

    public function approve(User $owner, ActivityLogService $logger): RedirectResponse
    {
        abort_unless($owner->role === 'owner', 404);

        $owner->update([
            'is_active' => true,
            'approved_at' => now(),
        ]);

        Mail::to($owner->email)->send(new OwnerStatusMail(
            owner: $owner,
            subjectLine: 'Akun Owner Anda Telah Disetujui',
            headline: 'Akun Anda Disetujui',
            statusLabel: 'Aktif',
            messageLine: 'Akun owner Anda sudah disetujui oleh admin dan akses panel telah dibuka.',
        ));
        $logger->log('owner_approval', 'approve', $owner, [
            'owner_name' => $owner->name,
            'owner_email' => $owner->email,
        ]);

        return back()->with('success', 'Owner berhasil disetujui dan email notifikasi sudah dikirim.');
    }

    public function activate(User $owner, ActivityLogService $logger): RedirectResponse
    {
        abort_unless($owner->role === 'owner' && $owner->approved_at, 404);

        $owner->update(['is_active' => true]);
        Mail::to($owner->email)->send(new OwnerStatusMail(
            owner: $owner,
            subjectLine: 'Akun Owner Anda Diaktifkan Kembali',
            headline: 'Akun Anda Aktif Kembali',
            statusLabel: 'Aktif',
            messageLine: 'Akses owner Anda telah diaktifkan kembali oleh admin. Silakan login untuk melanjutkan penggunaan panel.',
        ));
        $logger->log('owner_approval', 'activate', $owner, [
            'owner_name' => $owner->name,
            'owner_email' => $owner->email,
        ]);

        return back()->with('success', 'Owner berhasil diaktifkan kembali.');
    }

    public function deactivate(User $owner, ActivityLogService $logger): RedirectResponse
    {
        abort_unless($owner->role === 'owner' && $owner->approved_at, 404);

        $owner->forceFill([
            'is_active' => false,
            'remember_token' => Str::random(60),
        ])->save();

        if (Schema::hasTable('sessions') && config('session.driver') === 'database') {
            DB::table(config('session.table', 'sessions'))
                ->where('user_id', $owner->id)
                ->delete();
        }

        Mail::to($owner->email)->send(new OwnerStatusMail(
            owner: $owner,
            subjectLine: 'Akun Owner Anda Dinonaktifkan',
            headline: 'Akun Anda Dinonaktifkan',
            statusLabel: 'Nonaktif',
            messageLine: 'Akses owner Anda telah dinonaktifkan oleh admin. Anda tidak bisa login sampai akun diaktifkan kembali.',
        ));
        $logger->log('owner_approval', 'deactivate', $owner, [
            'owner_name' => $owner->name,
            'owner_email' => $owner->email,
        ]);

        return back()->with('success', 'Owner berhasil dinonaktifkan. Owner tidak bisa login sampai diaktifkan kembali.');
    }

    private function ownerCard(User $owner): array
    {
        $studentCount = Student::withoutGlobalScopes()->where('owner_id', $owner->id)->count();
        $packageCount = Package::withoutGlobalScopes()->where('owner_id', $owner->id)->count();
        $sessionCount = TestSession::withoutGlobalScopes()->where('owner_id', $owner->id)->count();
        $questionCount = PsychologyQuestion::withoutGlobalScopes()->where('owner_id', $owner->id)->count();
        $announcementCount = Announcement::withoutGlobalScopes()->where('owner_id', $owner->id)->count();
        $resultCount = TestResult::withoutGlobalScopes()->where('owner_id', $owner->id)->count();
        $violationCount = Violation::withoutGlobalScopes()->where('owner_id', $owner->id)->count();
        $activityCount = ActivityLog::withoutGlobalScopes()->where('owner_id', $owner->id)->count();
        $lastActivity = ActivityLog::withoutGlobalScopes()->where('owner_id', $owner->id)->latest()->first();

        return [
            'owner' => $owner,
            'status' => $owner->approved_at ? 'approved' : 'pending',
            'counts' => [
                'students' => $studentCount,
                'packages' => $packageCount,
                'sessions' => $sessionCount,
                'questions' => $questionCount,
                'announcements' => $announcementCount,
                'results' => $resultCount,
                'violations' => $violationCount,
                'activities' => $activityCount,
            ],
            'total_records' => $studentCount + $packageCount + $sessionCount + $questionCount + $announcementCount + $resultCount + $violationCount,
            'last_activity' => $lastActivity?->action ? ucwords(str_replace('_', ' ', $lastActivity->action)) . ' ' . ucwords(str_replace('_', ' ', $lastActivity->module)) : '-',
            'last_activity_at' => $lastActivity?->created_at,
        ];
    }
}
