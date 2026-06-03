<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class PsychologyQuestionSeeder extends Seeder
{
    public function run(): void
    {
        $packages = Package::where('is_active', true)->pluck('id', 'code')->all();

        if (empty($packages)) {
            throw new RuntimeException('Tidak ada package aktif. Jalankan seeder package terlebih dahulu.');
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('psychology_option_weights')->truncate();
        DB::table('student_psychology_answers')->truncate();
        DB::table('psychology_question_options')->truncate();
        DB::table('psychology_questions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $questions = [
            [
                'question' => 'Saat mengerjakan tugas kelompok, saya paling nyaman ketika ...',
                'weights' => [
                    'A' => ['text' => 'menganalisis data atau mencari pola yang paling logis', 'focus' => 'STEM'],
                    'B' => ['text' => 'mencermati kebutuhan anggota tim dan menjaga kerja sama tetap sehat', 'focus' => 'HEALTH'],
                    'C' => ['text' => 'menyusun argumen dan melihat dampak keputusan bagi banyak orang', 'focus' => 'SOSHUM'],
                    'D' => ['text' => 'menyampaikan ide, mempresentasikan hasil, atau membuat narasi yang menarik', 'focus' => 'LANG'],
                    'E' => ['text' => 'menunggu arahan lalu ikut membantu bagian yang paling dibutuhkan', 'focus' => 'HEALTH'],
                ],
            ],
            [
                'question' => 'Topik pembahasan yang paling sering membuat saya penasaran adalah ...',
                'weights' => [
                    'A' => ['text' => 'cara kerja alat, rumus, atau fenomena alam', 'focus' => 'STEM'],
                    'B' => ['text' => 'tubuh manusia, kesehatan, dan gaya hidup sehat', 'focus' => 'HEALTH'],
                    'C' => ['text' => 'masalah sosial, perilaku masyarakat, dan ekonomi', 'focus' => 'SOSHUM'],
                    'D' => ['text' => 'bahasa, budaya, dan cara orang berkomunikasi', 'focus' => 'LANG'],
                    'E' => ['text' => 'hal-hal praktis yang langsung bisa diterapkan', 'focus' => 'STEM'],
                ],
            ],
            [
                'question' => 'Jika diminta membuat proyek mandiri, saya lebih tertarik untuk ...',
                'weights' => [
                    'A' => ['text' => 'merancang percobaan atau prototipe sederhana', 'focus' => 'STEM'],
                    'B' => ['text' => 'membuat edukasi tentang kesehatan atau lingkungan hidup', 'focus' => 'HEALTH'],
                    'C' => ['text' => 'meneliti kebiasaan masyarakat dan menyusun laporannya', 'focus' => 'SOSHUM'],
                    'D' => ['text' => 'membuat artikel, video presentasi, atau karya bilingual', 'focus' => 'LANG'],
                    'E' => ['text' => 'menyusun proyek yang rapi dan punya banyak langkah jelas', 'focus' => 'STEM'],
                ],
            ],
            [
                'question' => 'Ketika menghadapi masalah, langkah yang paling sering saya lakukan lebih dulu adalah ...',
                'weights' => [
                    'A' => ['text' => 'mengurai masalah menjadi bagian-bagian kecil dan mencari pola sebab akibat', 'focus' => 'STEM'],
                    'B' => ['text' => 'melihat dampaknya terhadap orang lain dan mencari solusi yang aman', 'focus' => 'HEALTH'],
                    'C' => ['text' => 'mempertimbangkan kondisi sosial, aturan, dan kepentingan bersama', 'focus' => 'SOSHUM'],
                    'D' => ['text' => 'mendiskusikan, menuliskan, atau mengomunikasikan inti masalahnya', 'focus' => 'LANG'],
                    'E' => ['text' => 'mengamati dulu situasinya sebelum memutuskan langkah berikutnya', 'focus' => 'SOSHUM'],
                ],
            ],
            [
                'question' => 'Kegiatan ekstrakurikuler yang paling sesuai dengan diri saya cenderung ...',
                'weights' => [
                    'A' => ['text' => 'robotik, sains club, atau coding', 'focus' => 'STEM'],
                    'B' => ['text' => 'PMR, kader kesehatan, atau kegiatan kepedulian', 'focus' => 'HEALTH'],
                    'C' => ['text' => 'debat sosial, OSIS, atau kewirausahaan', 'focus' => 'SOSHUM'],
                    'D' => ['text' => 'english club, jurnalistik, atau teater', 'focus' => 'LANG'],
                    'E' => ['text' => 'kegiatan yang melatih kerja sama dan keterampilan umum', 'focus' => 'HEALTH'],
                ],
            ],
        ];

        $weightMap = [
            'STEM' => ['STEM' => 10, 'HEALTH' => 6, 'SOSHUM' => 2, 'LANG' => 2],
            'HEALTH' => ['STEM' => 5, 'HEALTH' => 10, 'SOSHUM' => 4, 'LANG' => 2],
            'SOSHUM' => ['STEM' => 2, 'HEALTH' => 4, 'SOSHUM' => 10, 'LANG' => 5],
            'LANG' => ['STEM' => 2, 'HEALTH' => 2, 'SOSHUM' => 5, 'LANG' => 10],
        ];

        foreach ($questions as $index => $questionData) {
            $questionId = DB::table('psychology_questions')->insertGetId($this->timestampedRow([
                'question' => $questionData['question'],
                'image_path' => null,
                'order' => $index + 1,
                'is_active' => true,
            ]));

            foreach ($questionData['weights'] as $label => $optionData) {
                $optionId = DB::table('psychology_question_options')->insertGetId($this->timestampedRow([
                    'psychology_question_id' => $questionId,
                    'label' => $label,
                    'option_text' => $optionData['text'],
                ]));

                foreach ($packages as $code => $packageId) {
                    DB::table('psychology_option_weights')->insert([
                        'psychology_question_option_id' => $optionId,
                        'package_id' => $packageId,
                        'weight' => $weightMap[$optionData['focus']][match ($code) {
                            'A' => 'STEM',
                            'B' => 'HEALTH',
                            'C' => 'SOSHUM',
                            default => 'LANG',
                        }],
                    ]);
                }
            }
        }
    }

    private function timestampedRow(array $row): array
    {
        return $row + [
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
