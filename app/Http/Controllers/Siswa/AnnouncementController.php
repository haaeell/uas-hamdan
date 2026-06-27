<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\AnnouncementResponse;
use App\Models\Package;
use App\Models\Setting;
use App\Models\TestResult;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;

class AnnouncementController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student()
            ->with([
                'biodata',
                'packageChoice.firstPackage',
                'packageChoice.secondPackage',
            ])
            ->firstOrFail();

        abort_if($student->status !== 'completed', 403, 'Pengumuman belum tersedia untuk status Anda.');

        $announcement = Announcement::where('type', 'final')
            ->where('is_published', true)
            ->latest('published_at')
            ->latest('id')
            ->first();
        $announcementIsOpen = $announcement?->published_at?->lte(now()) ?? false;

        $classStudent = $student->classStudent()
            ->with('classGroup.package')
            ->first();

        $testResult = TestResult::with(['recommendedPackage', 'finalPackage'])
            ->where('student_id', $student->id)
            ->first();

        $packageNames = Package::pluck('name', 'id');

        $psychologyScores = collect($testResult?->psychology_scores ?? [])
            ->map(function ($score, $packageId) use ($packageNames) {
                return [
                    'package' => $packageNames->get((int) $packageId, 'Jurusan #' . $packageId),
                    'score' => $score,
                ];
            })
            ->sortByDesc('score')
            ->values();

        $whatsappNumber = preg_replace('/\D+/', '', (string) Setting::getSetting('whatsapp_number', ''));
        $whatsappUrl = $whatsappNumber
            ? 'https://wa.me/' . $whatsappNumber . '?text=' . urlencode('Halo, saya ' . $student->name . ' ingin bertanya tentang pengumuman hasil.')
            : null;

        return view('siswa.announcements.index', compact(
            'student',
            'announcement',
            'classStudent',
            'testResult',
            'psychologyScores',
            'whatsappUrl',
            'announcementIsOpen'
        ));
    }

    public function accept(Announcement $announcement)
    {
        $student = auth()->user()->student;
        abort_if($student->status !== 'completed', 403, 'Pengumuman belum tersedia untuk status Anda.');
        abort_if(!$announcement->is_published, 404);

        AnnouncementResponse::updateOrCreate(
            [
                'announcement_id' => $announcement->id,
                'student_id' => $student->id,
            ],
            [
                'response' => 'accepted',
                'responded_at' => now(),
            ]
        );

        return back()->with('success', 'Pengumuman berhasil diterima.');
    }

    public function downloadLetter(Announcement $announcement)
    {
        $student = auth()->user()->student()
            ->with([
                'biodata',
                'result.recommendedPackage',
                'result.finalPackage',
            ])
            ->firstOrFail();

        abort_if($student->status !== 'completed', 403, 'Surat belum tersedia untuk status Anda.');
        abort_if(!$announcement->is_published, 404);
        abort_if($announcement->type !== 'final', 404);
        abort_if(!($announcement->published_at?->lte(now()) ?? false), 403, 'Surat belum tersedia sebelum jadwal pengumuman dibuka.');

        $response = AnnouncementResponse::where('announcement_id', $announcement->id)
            ->where('student_id', $student->id)
            ->first();

        $classStudent = $student->classStudent()
            ->with(['classGroup.package', 'package'])
            ->first();

        abort_if(!$classStudent, 404, 'Data jurusan dan kelas belum tersedia.');

        $today = Carbon::now();
        $assetDataUri = function (string $path): ?string {
            if (!is_file($path)) {
                return null;
            }

            $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            $mimeType = match ($extension) {
                'jpg', 'jpeg' => 'image/jpeg',
                'gif' => 'image/gif',
                'webp' => 'image/webp',
                default => 'image/png',
            };

            return 'data:' . $mimeType . ';base64,' . base64_encode(file_get_contents($path));
        };

        $pdf = Pdf::loadView('pdfs.student-announcement-letter', [
            'student' => $student,
            'announcement' => $announcement,
            'classStudent' => $classStudent,
            'response' => $response,
            'schoolName' => Setting::getSetting('school_name', 'Sekolah Menengah Atas'),
            'appName' => Setting::getSetting('app_name', 'Sistem Pemilihan Jurusan'),
            'supportContact' => Setting::getSetting('support_contact', 'Hubungi admin sekolah'),
            'issuedDate' => $today->translatedFormat('d F Y'),
            'letterNumber' => '400.3.8/241' . '/SMA-YAH/' . $today->format('Y'),
            'principalName' => 'Yanto Susanto, S. Pd., M. IP.',
            'principalIdentity' => 'NIKA. 19831205 200801 0031',
            'logoDataUri' => Setting::logoDataUri(),
            'kopDataUri' => $assetDataUri(public_path('images/kop.jpeg')),
            'footerDataUri' => $assetDataUri(public_path('images/footer.jpeg')),
            'stampDataUri' => $assetDataUri(public_path('images/surat-pengumuman-cap.png'))
                ?: $assetDataUri(public_path('images/cap.png')),
            'signatureDataUri' => $assetDataUri(public_path('images/surat-pengumuman-ttd.png'))
                ?: $assetDataUri(public_path('images/ttd.png')),
        ])->setPaper('a4', 'portrait');

        $filename = 'surat_pengumuman_peminatan_' . $student->nisn . '.pdf';

        return $pdf->stream($filename);
    }
}
