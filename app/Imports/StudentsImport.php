<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StudentsImport implements ToCollection, WithChunkReading, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    public function collection(Collection $rows): void
    {
        $cleanRows = $rows
            ->map(function ($row) {
                $isActive = $row['is_active'] ?? null;

                return [
                    'name' => trim((string) ($row['name'] ?? '')),
                    'nisn' => trim((string) ($row['nisn'] ?? '')),
                    'nis' => trim((string) ($row['nis'] ?? '')) ?: null,
                    'origin_class' => strtoupper(trim((string) ($row['origin_class'] ?? ''))),
                    'password' => trim((string) ($row['password'] ?? '')) ?: '12345678',
                    'is_active' => $isActive === null || trim((string) $isActive) === ''
                        ? true
                        : $this->normalizeIsActive($isActive),
                ];
            })
            ->filter(fn(array $row) => $row['name'] !== '' && $row['nisn'] !== '');

        if ($cleanRows->isEmpty()) {
            return;
        }

        $nisns = $cleanRows->pluck('nisn')->unique()->values();
        $existingUserNisns = User::whereIn('nisn', $nisns)->pluck('nisn')->all();
        $existingStudentNisns = Student::whereIn('nisn', $nisns)->pluck('nisn')->all();
        $blockedNisns = array_flip(array_unique([...$existingUserNisns, ...$existingStudentNisns]));

        DB::transaction(function () use ($cleanRows, &$blockedNisns) {
            foreach ($cleanRows as $row) {
                if (isset($blockedNisns[$row['nisn']])) {
                    continue;
                }

                $user = User::create([
                    'name' => $row['name'],
                    'nisn' => $row['nisn'],
                    'password' => $row['password'],
                    'role' => 'siswa',
                    'is_active' => $row['is_active'],
                ]);

                Student::create([
                    'user_id' => $user->id,
                    'nisn' => $row['nisn'],
                    'nis' => $row['nis'],
                    'name' => $row['name'],
                    'origin_class' => $row['origin_class'],
                    'status' => 'onboarding',
                ]);

                $blockedNisns[$row['nisn']] = true;
            }
        });
    }

    public function rules(): array
    {
        return [
            '*.name' => ['required', 'string', 'max:150'],
            '*.nisn' => ['required', 'string', 'max:30'],
            '*.nis' => ['nullable', 'string', 'max:30'],
            '*.origin_class' => ['required', 'string', 'max:20'],
            '*.password' => ['nullable', 'string', 'min:6'],
            '*.is_active' => ['nullable', Rule::in([1, 0, '1', '0', true, false, 'true', 'false', 'aktif', 'nonaktif', 'active', 'inactive'])],
        ];
    }

    public function chunkSize(): int
    {
        return 200;
    }

    private function normalizeIsActive(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        return in_array(strtolower(trim((string) $value)), ['1', 'true', 'aktif', 'active', 'yes', 'ya'], true);
    }
}
