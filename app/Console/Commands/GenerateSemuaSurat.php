<?php
// app/Console/Commands/GenerateSemuaSurat.php

namespace App\Console\Commands;

use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GenerateSemuaSurat extends Command
{
    protected $signature   = 'surat:generate';
    protected $description = 'Generate surat kelulusan semua siswa';

    public function handle()
    {
        set_time_limit(0);
        ini_set('memory_limit', '512M');

        $lockFile = storage_path('app/generate_surat.lock');
        file_put_contents($lockFile, getmypid());

        try {
            $sharedData = [
                'school_year'    => '2025/2026',
                'rapat_tanggal'  => '4 Mei 2026',
                'issued_at'      => '4 Mei 2026',
                'kepala_sekolah' => 'Yanto Susanto, S.Pd., M.IP.',
                'nip_kepsek'     => '...',
                'logo_url' => \App\Models\Setting::logoDataUri(),
                'cap_url'  => 'data:image/png;base64,'  . base64_encode(file_get_contents(public_path('images/cap.png'))),
                'ttd_url'  => 'data:image/png;base64,'  . base64_encode(file_get_contents(public_path('images/ttd.png'))),
                'kop'      => 'data:image/jpeg;base64,' . base64_encode(file_get_contents(public_path('images/kop.jpeg'))),
                'footer'   => 'data:image/jpeg;base64,' . base64_encode(file_get_contents(public_path('images/footer.jpeg'))),
            ];

            $options = [
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => false,
                'defaultFont'          => 'DejaVu Sans',
                'dpi'                  => 96,
            ];

            $total     = Student::count();
            $generated = 0;

            Student::chunk(50, function ($students) use ($sharedData, $options, $total, &$generated, $lockFile) {
                foreach ($students as $student) {
                    $data = array_merge($sharedData, ['student' => $student]);

                    $pdf = Pdf::loadView('certificate', $data)
                        ->setPaper('a4', 'portrait')
                        ->setOptions($options);

                    $filename = 'Surat_Kelulusan_' . str_replace(' ', '_', $student->nama) . '_' . $student->nis . '.pdf';
                    $path     = 'surat/' . $filename;

                    Storage::disk('public')->put($path, $pdf->output());
                    $student->update(['file_surat' => $path]);

                    $generated++;

                    file_put_contents($lockFile, json_encode([
                        'pid'       => getmypid(),
                        'generated' => $generated,
                        'total'     => $total,
                    ]));

                    $this->info("[{$generated}/{$total}] ✓ {$student->nama}");
                }
            });
        } finally {
            if (file_exists($lockFile)) unlink($lockFile);
            $this->info('Selesai!');
        }
    }
}
