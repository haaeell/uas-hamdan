<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PsychologyQuestionsTemplateExport implements FromArray, ShouldAutoSize, WithHeadings, WithStyles
{
    public function __construct(private readonly Collection $packages)
    {
    }

    public function headings(): array
    {
        return array_merge(
            ['question_group', 'question', 'option_label', 'option_text'],
            $this->packages->map(fn ($package) => 'weight_' . strtolower($package->code))->all()
        );
    }

    public function array(): array
    {
        $weightSets = [
            $this->packages->map(fn ($_, $i) => max(10 - $i * 2, 0))->all(),
            $this->packages->map(fn ($_, $i) => [8, 10, 4, 6, 0][$i] ?? 0)->all(),
            $this->packages->map(fn ($_, $i) => [6, 4, 10, 2, 8][$i] ?? 0)->all(),
            $this->packages->map(fn ($_, $i) => [4, 6, 2, 10, 6][$i] ?? 0)->all(),
            $this->packages->map(fn ($_, $i) => [2, 0, 8, 6, 10][$i] ?? 0)->all(),
        ];

        $options = [
            ['A', 'Sangat sesuai dengan diri saya'],
            ['B', 'Cukup sesuai dengan diri saya'],
            ['C', 'Kurang sesuai dengan diri saya'],
            ['D', 'Tidak sesuai dengan diri saya'],
            ['E', 'Sangat tidak sesuai dengan diri saya'],
        ];

        return array_map(
            fn ($opt, $weights) => array_merge(
                ['PSI-001', 'Saya lebih suka aktivitas yang melibatkan eksperimen.', $opt[0], $opt[1]],
                $weights
            ),
            $options,
            $weightSets
        );
    }

    public function styles(Worksheet $sheet): array
    {
        $styles = [
            1 => ['font' => ['bold' => true]],
        ];

        $weightStartCol = 5;
        $weightEndCol   = $weightStartCol + $this->packages->count() - 1;

        if ($this->packages->isNotEmpty()) {
            $startLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($weightStartCol);
            $endLetter   = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($weightEndCol);

            $sheet->getStyle("{$startLetter}1:{$endLetter}1")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => '1E40AF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'DBEAFE'],
                ],
            ]);
        }

        return $styles;
    }
}
