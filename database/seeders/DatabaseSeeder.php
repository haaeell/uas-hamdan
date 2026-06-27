<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        foreach (
            [
                'activity_logs',
                'violations',
                'objections',
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
                'settings',
                'users',
            ] as $table
        ) {
            DB::table($table)->truncate();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        DB::transaction(function () {
            $platformAdminId = $this->seedPlatformAdmin();
            $ownerId = $this->seedOwner();
            $this->seedSettings($ownerId);
            $packages = $this->seedPackages($ownerId);
            $this->seedPackageSubjects($packages);
            $this->seedTestSessions($ownerId);
            $this->seedSampleStudents($ownerId);
            $this->seedPsychologyQuestions($packages);
        });
    }

    private function seedSettings(int $ownerId): void
    {
        $settings = [
            ['owner_id' => $ownerId, 'key' => 'app_name', 'value' => 'Sistem Pemilihan Jurusan', 'group' => 'general'],
            ['owner_id' => $ownerId, 'key' => 'school_name', 'value' => 'SMA Negeri 1 Contoh', 'group' => 'general'],
            ['owner_id' => $ownerId, 'key' => 'support_contact', 'value' => 'Admin BK / WA 0812-0000-0000', 'group' => 'general'],
            ['owner_id' => $ownerId, 'key' => 'whatsapp_number', 'value' => '6281200000000', 'group' => 'general'],
            ['owner_id' => $ownerId, 'key' => 'theme_color', 'value' => '#2563eb', 'group' => 'general'],
            ['owner_id' => $ownerId, 'key' => 'login_help_text', 'value' => 'Masuk menggunakan email admin atau owner yang sudah terdaftar.', 'group' => 'general'],
            ['owner_id' => $ownerId, 'key' => 'psychology_duration_minutes', 'value' => '60', 'group' => 'cbt'],
            ['owner_id' => $ownerId, 'key' => 'cbt_auto_submit_violation_limit', 'value' => '3', 'group' => 'cbt'],
            ['owner_id' => $ownerId, 'key' => 'cbt_force_fullscreen', 'value' => '1', 'group' => 'cbt'],
            ['owner_id' => $ownerId, 'key' => 'cbt_warning_message', 'value' => 'Perpindahan tab, keluar fullscreen, atau aktivitas mencurigakan akan dicatat oleh sistem.', 'group' => 'cbt'],
            ['owner_id' => $ownerId, 'key' => 'student_help_text', 'value' => 'Pastikan perangkat stabil, gunakan koneksi yang baik, dan hubungi admin bila ada kendala teknis.', 'group' => 'student'],
        ];

        DB::table('settings')->insert($this->withTimestamps($settings));
    }

    private function seedPlatformAdmin(): int
    {
        $user = User::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
            'approved_at' => now(),
            'exam_token' => Str::random(32),
        ]);

        return $user->id;
    }

    private function seedOwner(): int
    {
        $user = User::create([
            'name' => 'Owner Demo',
            'email' => 'owner@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'is_active' => true,
            'approved_at' => now(),
            'exam_token' => Str::random(32),
        ]);

        $user->forceFill(['owner_id' => $user->id])->save();

        return $user->id;
    }

    private function seedPackages(int $ownerId): array
    {
        $packages = [
            [
                'code' => 'A',
                'name' => 'Kelompok A',
                'description' => 'Fokus pada penalaran ilmiah, eksperimen, dan pemecahan masalah kuantitatif.',
                'color' => '#2563eb',
            ],
            [
                'code' => 'B',
                'name' => 'Kelompok B',
                'description' => 'Fokus pada biologi, kesehatan, observasi detail, dan kepedulian terhadap manusia.',
                'color' => '#16a34a',
            ],
            [
                'code' => 'C',
                'name' => 'Kelompok C',
                'description' => 'Fokus pada ekonomi, sosiologi, sejarah, dan analisis isu sosial.',
                'color' => '#f97316',
            ],
            [
                'code' => 'D',
                'name' => 'Kelompok D',
                'description' => 'Fokus pada bahasa, komunikasi, presentasi, dan kajian budaya.',
                'color' => '#7c3aed',
            ],
        ];

        $packageIds = [];

        foreach ($packages as $package) {
            $packageIds[$package['code']] = DB::table('packages')->insertGetId($this->timestampedRow($package + [
                'owner_id' => $ownerId,
                'is_active' => true,
            ]));
        }

        return $packageIds;
    }

    private function seedPackageSubjects(array $packages): void
    {
        $subjects = [
            'A' => ['Fisika', 'Kimia', 'Mat Lanjut', 'Geografi'],
            'B' => ['Kimia', 'Biologi', 'Sosiologi', 'B. Ing. Lanjut'],
            'C' => ['Sosiologi', 'Ekonomi', 'B. Ing. Lanjut', 'B. Jerman'],
            'D' => ['Ekonomi', 'Geografi', 'Sejarah Lanjut', 'B. Jerman'],
        ];

        foreach ($subjects as $code => $rows) {
            foreach ($rows as $index => $subjectName) {
                DB::table('package_subjects')->insert($this->timestampedRow([
                    'package_id' => $packages[$code],
                    'subject_name' => $subjectName,
                    'order' => $index + 1,
                ]));
            }
        }
    }

    private function seedTestSessions(int $ownerId): void
    {
        $sessions = [
            [
                'name' => 'Sesi Pagi Gelombang 1',
                'test_date' => now()->toDateString(),
                'start_time' => '07:30:00',
                'end_time' => '10:30:00',
                'test_type' => 'both',
                'is_active' => true,
                'owner_id' => $ownerId,
                'classes' => ['X A', 'X B', 'X C', 'X D'],
            ],
            [
                'name' => 'Sesi Pagi Gelombang 2',
                'test_date' => now()->toDateString(),
                'start_time' => '10:45:00',
                'end_time' => '13:45:00',
                'test_type' => 'both',
                'is_active' => true,
                'owner_id' => $ownerId,
                'classes' => ['X E', 'X F', 'X G', 'X H'],
            ],
        ];

        foreach ($sessions as $session) {
            $classes = $session['classes'];
            unset($session['classes']);

            $sessionId = DB::table('test_sessions')->insertGetId($this->timestampedRow($session));

            foreach ($classes as $class) {
                DB::table('test_session_classes')->insert($this->timestampedRow([
                    'test_session_id' => $sessionId,
                    'origin_class' => $class,
                ]));
            }
        }
    }

    private function seedSampleStudents(int $ownerId): void
    {
        $students = [
            ['name' => 'Alya Putri Maharani', 'nisn' => '2026000001', 'nis' => '260001', 'origin_class' => 'X A', 'status' => 'onboarding'],
            ['name' => 'Bagas Pratama', 'nisn' => '2026000002', 'nis' => '260002', 'origin_class' => 'X B', 'status' => 'onboarding'],
            ['name' => 'Citra Lestari', 'nisn' => '2026000003', 'nis' => '260003', 'origin_class' => 'X C', 'status' => 'onboarding'],
            ['name' => 'Dimas Ramadhan', 'nisn' => '2026000004', 'nis' => '260004', 'origin_class' => 'X D', 'status' => 'onboarding'],
            ['name' => 'Elsa Permata', 'nisn' => '2026000005', 'nis' => '260005', 'origin_class' => 'X E', 'status' => 'onboarding'],
            ['name' => 'Farhan Rizky', 'nisn' => '2026000006', 'nis' => '260006', 'origin_class' => 'X F', 'status' => 'onboarding'],
        ];

        foreach ($students as $index => $student) {
            $user = User::create([
                'owner_id' => $ownerId,
                'name' => $student['name'],
                'nisn' => $student['nisn'],
                'password' => Hash::make('12345678'),
                'role' => 'siswa',
                'is_active' => true,
            ]);

            $studentId = DB::table('students')->insertGetId($this->timestampedRow([
                'owner_id' => $ownerId,
                'user_id' => $user->id,
                'nisn' => $student['nisn'],
                'nis' => $student['nis'],
                'name' => $student['name'],
                'origin_class' => $student['origin_class'],
                'status' => $student['status'],
            ]));
        }
    }

    private function seedPsychologyQuestions(array $packages): void
    {
        $ownerId = DB::table('packages')->whereIn('id', array_values($packages))->value('owner_id');

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
                'owner_id' => $ownerId,
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

    private function withTimestamps(array $rows): array
    {
        return array_map(fn(array $row) => $this->timestampedRow($row), $rows);
    }
}
