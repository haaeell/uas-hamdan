<?php

namespace App\Http\Controllers\Admin;

use App\Exports\StudentsExport;
use App\Exports\StudentsTemplateExport;
use App\Http\Controllers\Controller;
use App\Imports\StudentsImport;
use App\Models\Student;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with(['user', 'biodata', 'packageChoice.firstPackage', 'packageChoice.secondPackage'])
            ->latest()
            ->paginate(20);

        return view('admin.students.index', compact('students'));
    }

    public function store(Request $request, ActivityLogService $logger)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'nisn' => ['required', 'string', 'max:30', 'unique:users,nisn', 'unique:students,nisn'],
            'nis' => ['nullable', 'string', 'max:30'],
            'origin_class' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:6'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($validated, $request, $logger) {
            $user = User::create([
                'name' => $validated['name'],
                'nisn' => $validated['nisn'],
                'password' => Hash::make($validated['password']),
                'role' => 'siswa',
                'is_active' => $request->boolean('is_active'),
            ]);

            $student = Student::create([
                'user_id' => $user->id,
                'nisn' => $validated['nisn'],
                'nis' => $validated['nis'] ?? null,
                'name' => $validated['name'],
                'origin_class' => strtoupper($validated['origin_class']),
                'status' => 'onboarding',
            ]);

            $logger->log('student', 'create', $student);
        });

        return back()->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function update(Request $request, Student $student, ActivityLogService $logger)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'nisn' => [
                'required',
                'string',
                'max:30',
                Rule::unique('students', 'nisn')->ignore($student->id),
                Rule::unique('users', 'nisn')->ignore($student->user_id),
            ],
            'nis' => ['nullable', 'string', 'max:30'],
            'origin_class' => ['required', 'string', 'max:20'],
            'password' => ['nullable', 'string', 'min:6'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($validated, $request, $student, $logger) {
            $student->update([
                'name' => $validated['name'],
                'nisn' => $validated['nisn'],
                'nis' => $validated['nis'] ?? null,
                'origin_class' => strtoupper($validated['origin_class']),
            ]);

            $userData = [
                'name' => $validated['name'],
                'nisn' => $validated['nisn'],
                'is_active' => $request->boolean('is_active'),
            ];

            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $student->user->update($userData);

            $logger->log('student', 'update', $student);
        });

        return back()->with('success', 'Siswa berhasil diperbarui.');
    }

    public function destroy(Student $student, ActivityLogService $logger)
    {
        DB::transaction(function () use ($student, $logger) {
            $logger->log('student', 'delete', $student);
            $this->deleteStudents(collect([$student]));
        });

        return back()->with('success', 'Siswa berhasil dihapus.');
    }

    public function bulkDelete(Request $request, ActivityLogService $logger)
    {
        $validated = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['exists:students,id'],
        ]);

        $students = Student::whereIn('id', $validated['ids'])->with(['user', 'selfie'])->get();

        DB::transaction(function () use ($students, $logger) {
            foreach ($students as $student) {
                $logger->log('student', 'delete', $student);
            }

            $this->deleteStudents($students);
        });

        return back()->with('success', 'Siswa terpilih berhasil dihapus.');
    }

    public function bulkActivate(Request $request)
    {
        $validated = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['exists:students,id'],
        ]);

        User::whereHas('student', fn($q) => $q->whereIn('id', $validated['ids']))
            ->update(['is_active' => true]);

        return back()->with('success', 'Siswa terpilih berhasil diaktifkan.');
    }

    public function bulkDeactivate(Request $request)
    {
        $validated = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['exists:students,id'],
        ]);

        User::whereHas('student', fn($q) => $q->whereIn('id', $validated['ids']))
            ->update(['is_active' => false]);

        return back()->with('success', 'Siswa terpilih berhasil dinonaktifkan.');
    }

    public function downloadTemplate()
    {
        return Excel::download(new StudentsTemplateExport(), 'template_siswa.xlsx');
    }

    public function export()
    {
        return Excel::download(new StudentsExport(), 'data_siswa.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv,txt'],
        ]);

        $import = new StudentsImport();

        Excel::import($import, $request->file('file'));

        if ($import->getImportedCount() > 0) {
            $message = "Import siswa berhasil: {$import->getImportedCount()} data masuk";
        } else {
            $message = 'Import selesai: tidak ada data baru yang masuk';
        }

        if ($import->getSkippedCount() > 0) {
            $message .= ", {$import->getSkippedCount()} baris dilewati karena kosong, duplikat, atau formatnya tidak valid.";
        } else {
            $message .= '.';
        }

        return back()->with('success', $message);
    }

    private function deleteStudents(Collection $students): void
    {
        foreach ($students as $student) {
            if ($student->selfie?->path && Storage::disk('public')->exists($student->selfie->path)) {
                Storage::disk('public')->delete($student->selfie->path);
            }

            if ($student->user) {
                $student->user->delete();
                continue;
            }

            $student->delete();
        }
    }
}
