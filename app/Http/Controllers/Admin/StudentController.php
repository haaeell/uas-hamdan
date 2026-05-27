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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
            'nisn' => ['required', 'string', 'max:30', 'unique:students,nisn,' . $student->id],
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

            $student->user?->delete();
            $student->delete();
        });

        return back()->with('success', 'Siswa berhasil dihapus.');
    }

    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['exists:students,id'],
        ]);

        $students = Student::whereIn('id', $validated['ids'])->with('user')->get();

        DB::transaction(function () use ($students) {
            foreach ($students as $student) {
                $student->user?->delete();
                $student->delete();
            }
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

        Excel::import(new StudentsImport(), $request->file('file'));

        return back()->with('success', 'Import siswa berhasil.');
    }
}
