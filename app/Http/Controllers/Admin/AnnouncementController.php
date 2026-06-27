<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Services\ActivityLogService;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcement = Announcement::where('type', 'final')
            ->latest('updated_at')
            ->first();

        return view('admin.announcements.index', compact('announcement'));
    }

    public function store(Request $request, ActivityLogService $logger)
    {
        $data = $this->validatedFinalAnnouncementData($request);

        $announcement = Announcement::where('type', 'final')
            ->latest('updated_at')
            ->first();

        if ($announcement) {
            $announcement->update($data);
            $action = 'update';
            $message = 'Pengumuman final berhasil diperbarui dan akan dibuka sesuai tanggal.';
        } else {
            $announcement = Announcement::create($data);
            $action = 'create';
            $message = 'Pengumuman final berhasil dibuat dan akan dibuka sesuai tanggal.';
        }

        $logger->log('announcement', $action, $announcement);

        return back()->with('success', $message);
    }

    public function update(Request $request, Announcement $announcement, ActivityLogService $logger)
    {
        abort_if($announcement->type !== 'final', 404);

        $announcement->update($this->validatedFinalAnnouncementData($request));

        $logger->log('announcement', 'update', $announcement);

        return back()->with('success', 'Pengumuman final berhasil diperbarui dan akan dibuka sesuai tanggal.');
    }

    public function destroy(Announcement $announcement, ActivityLogService $logger)
    {
        abort_if($announcement->type !== 'final', 404);

        $logger->log('announcement', 'delete', $announcement);

        $announcement->delete();

        return back()->with('success', 'Pengumuman final berhasil dihapus.');
    }

    private function validatedFinalAnnouncementData(Request $request): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'content' => ['nullable', 'string'],
            'published_at' => ['required', 'date'],
        ]);

        return [
            'type' => 'final',
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'is_published' => true,
            'published_at' => Carbon::parse($validated['published_at']),
            'published_by' => auth()->id(),
        ];
    }
}
