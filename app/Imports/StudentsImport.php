<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToCollection, WithChunkReading, WithHeadingRow, SkipsEmptyRows
{
    private int $importedCount = 0;

    private int $skippedCount = 0;

    private array $seenNisns = [];

    public function collection(Collection $rows): void
    {
        $cleanRows = $rows
            ->map(function ($row) {
                $row = $row->toArray();
                $isActive = $this->valueFromRow($row, ['is_active', 'status', 'aktif']);

                return [
                    'name' => trim((string) $this->valueFromRow($row, ['name', 'nama'])),
                    'nisn' => trim((string) $this->valueFromRow($row, ['nisn'])),
                    'nis' => $this->normalizeNullableString($this->valueFromRow($row, ['nis'])),
                    'origin_class' => strtoupper(trim((string) $this->valueFromRow($row, ['origin_class', 'origin class', 'kelas_asal', 'kelas asal', 'kelas']))),
                    'password' => trim((string) ($row['password'] ?? '')) ?: '12345678',
                    'is_active' => $isActive === null || trim((string) $isActive) === ''
                        ? true
                        : $this->normalizeIsActive($isActive),
                ];
            })
            ->filter(function (array $row) {
                if ($this->isEmptyStudentRow($row)) {
                    $this->skippedCount++;

                    return false;
                }

                return true;
            })
            ->values();

        if ($cleanRows->isEmpty()) {
            return;
        }

        $nisns = $cleanRows->pluck('nisn')->unique()->values();
        $existingUserNisns = User::whereIn('nisn', $nisns)->pluck('nisn')->all();
        $existingStudentNisns = Student::whereIn('nisn', $nisns)->pluck('nisn')->all();
        $blockedNisns = array_flip(array_unique([...$existingUserNisns, ...$existingStudentNisns]));

        DB::transaction(function () use ($cleanRows, &$blockedNisns) {
            foreach ($cleanRows as $row) {
                if (!$this->isRowValid($row)) {
                    $this->skippedCount++;
                    continue;
                }

                if (isset($blockedNisns[$row['nisn']]) || isset($this->seenNisns[$row['nisn']])) {
                    $this->skippedCount++;
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
                $this->seenNisns[$row['nisn']] = true;
                $this->importedCount++;
            }
        });
    }

    public function chunkSize(): int
    {
        return 200;
    }

    public function getImportedCount(): int
    {
        return $this->importedCount;
    }

    public function getSkippedCount(): int
    {
        return $this->skippedCount;
    }

    private function normalizeIsActive(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        return in_array(strtolower(trim((string) $value)), ['1', 'true', 'aktif', 'active', 'yes', 'ya'], true);
    }

    private function isRowValid(array $row): bool
    {
        return Validator::make($row, [
            'name' => ['required', 'string', 'max:150'],
            'nisn' => ['required', 'string', 'max:30'],
            'nis' => ['nullable', 'string', 'max:30'],
            'origin_class' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:6'],
            'is_active' => ['required', 'boolean'],
        ])->passes();
    }

    private function isEmptyStudentRow(array $row): bool
    {
        return $row['name'] === ''
            && $row['nisn'] === ''
            && ($row['nis'] === null || $row['nis'] === '')
            && $row['origin_class'] === '';
    }

    private function normalizeNullableString(mixed $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    private function valueFromRow(array $row, array $keys): mixed
    {
        foreach ($keys as $key) {
            if (array_key_exists($key, $row)) {
                return $row[$key];
            }
        }

        return null;
    }
}
