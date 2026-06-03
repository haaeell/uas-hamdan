<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcademicQuestionSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('student_academic_answers')->truncate();
        DB::table('academic_question_options')->truncate();
        DB::table('academic_questions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $questions = [
            [
                'question' => 'Nilai dari 3x - 7 = 20 adalah ...',
                'correct' => 'B',
                'options' => ['A' => '7', 'B' => '9', 'C' => '11', 'D' => '13', 'E' => '15'],
            ],
            [
                'question' => 'Jika fungsi f(x) = 2x + 5, maka nilai f(4) adalah ...',
                'correct' => 'D',
                'options' => ['A' => '9', 'B' => '11', 'C' => '12', 'D' => '13', 'E' => '14'],
            ],
            [
                'question' => 'Sebuah segitiga memiliki alas 12 cm dan tinggi 8 cm. Luas segitiga tersebut adalah ...',
                'correct' => 'C',
                'options' => ['A' => '32 cm2', 'B' => '40 cm2', 'C' => '48 cm2', 'D' => '96 cm2', 'E' => '24 cm2'],
            ],
            [
                'question' => 'Persamaan garis yang melalui titik (0, 3) dan (2, 7) memiliki gradien ...',
                'correct' => 'A',
                'options' => ['A' => '2', 'B' => '3', 'C' => '4', 'D' => '5', 'E' => '6'],
            ],
            [
                'question' => 'Hukum Newton I menjelaskan bahwa benda akan tetap diam atau bergerak lurus beraturan jika ...',
                'correct' => 'B',
                'options' => [
                    'A' => 'diberi gaya yang makin besar',
                    'B' => 'resultan gaya yang bekerja padanya nol',
                    'C' => 'memiliki massa yang besar',
                    'D' => 'bergerak pada lintasan melingkar',
                    'E' => 'selalu dipercepat oleh gaya luar',
                ],
            ],
        ];

        foreach ($questions as $index => $questionData) {
            $questionId = DB::table('academic_questions')->insertGetId($this->timestampedRow([
                'question' => $questionData['question'],
                'image_path' => null,
                'order' => $index + 1,
                'is_active' => true,
            ]));

            foreach ($questionData['options'] as $label => $text) {
                DB::table('academic_question_options')->insert($this->timestampedRow([
                    'academic_question_id' => $questionId,
                    'label' => $label,
                    'option_text' => $text,
                    'is_correct' => $label === $questionData['correct'],
                ]));
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
