<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassGroup;
use App\Models\ClassStudent;
use App\Models\Package;
use App\Models\TestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TestResultController extends Controller
{
    public function index()
    {
        $packages = Package::where('is_active', true)->get();
        $classGroups = ClassGroup::with('package')->orderBy('name')->get();

        return view('admin.test-results.index', compact('packages', 'classGroups'));
    }

    public function data()
    {
        $query = TestResult::query()
            ->select('test_results.*')
            ->where('owner_id', auth()->id())
            ->with([
                'student.user',
                'student.biodata',
                'student.packageChoice.firstPackage',
                'student.packageChoice.secondPackage',
                'student.classStudent.classGroup',
                'recommendedPackage',
                'finalPackage',
            ])
            ->orderByDesc('test_results.created_at');

        $packages = Package::where('is_active', true)->get();

        return DataTables::eloquent($query)
            ->filterColumn('student_info', function ($query, $keyword) {
                $query->whereHas('student', function ($studentQuery) use ($keyword) {
                    $studentQuery->where(function ($inner) use ($keyword) {
                        $inner->where('name', 'like', '%' . $keyword . '%')
                            ->orWhere('nisn', 'like', '%' . $keyword . '%')
                            ->orWhere('origin_class', 'like', '%' . $keyword . '%');
                    });
                });
            })
            ->addColumn('student_info', function ($result) {
                $student = $result->student;

                return '
                    <div class="font-extrabold text-slate-900">' . e($student?->name ?? '-') . '</div>
                    <div class="text-[11px] text-slate-500">NISN: ' . e($student?->nisn ?? '-') . '</div>
                    <div class="text-[11px] text-slate-400">' . e($student?->origin_class ?? '-') . '</div>
                ';
            })
            ->addColumn('status_tes', fn () => '<span class="inline-flex px-2.5 py-1 rounded-lg bg-green-50 text-green-700 text-[11px] font-bold">Selesai</span>')
            ->addColumn('rekomendasi', function ($result) {
                return '
                    <span class="inline-flex px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 text-[11px] font-bold">
                        ' . e($result->recommendedPackage?->name ?? '-') . '
                    </span>
                ';
            })
            ->addColumn('final', function ($result) {
                return '
                    <span class="inline-flex px-2.5 py-1 rounded-lg bg-blue-600 text-white text-[11px] font-bold">
                        ' . e($result->finalPackage?->name ?? '-') . '
                    </span>
                ';
            })
            ->addColumn('kelas', function ($result) {
                $classStudent = $result->student?->classStudent;

                if (!$classStudent) {
                    return '<span class="text-slate-400 font-bold">Belum dibagi</span>';
                }

                return '
                    <div class="font-bold text-slate-900">' . e($classStudent->classGroup?->name ?? '-') . '</div>
                    <div class="text-[10px] text-blue-600 font-bold">
                        ' . ($classStudent->is_manual_override ? 'Manual' : 'Otomatis') . '
                    </div>
                ';
            })
            ->addColumn('aksi', function ($result) use ($packages) {
                $student = $result->student;
                $biodata = $student?->biodata;
                $classStudent = $student?->classStudent;

                $detail = [
                    'id' => $result->id,
                    'student_id' => $student?->id,
                    'name' => $student?->name,
                    'nisn' => $student?->nisn,
                    'origin_class' => $student?->origin_class,
                    'status' => $student?->status,

                    'birth_place' => $biodata?->birth_place,
                    'birth_date' => $biodata?->birth_date?->format('d-m-Y'),
                    'gender' => $biodata?->gender,
                    'phone' => $biodata?->phone,
                    'father_name' => $biodata?->father_name,
                    'mother_name' => $biodata?->mother_name,
                    'parent_phone' => $biodata?->parent_phone,

                     'first_choice' => $student?->packageChoice?->firstPackage?->name,
                     'second_choice' => $student?->packageChoice?->secondPackage?->name,
                     'post_graduation_plan' => $student?->packageChoice?->post_graduation_plan,

                    'recommended' => $result->recommendedPackage?->name,
                    'final' => $result->finalPackage?->name,
                    'final_package_id' => $result->final_package_id,

                    'class_name' => $classStudent?->classGroup?->name,
                    'class_group_id' => $classStudent?->class_group_id,
                    'distribution_type' => $classStudent
                        ? ($classStudent->is_manual_override ? 'Manual' : 'Otomatis')
                        : null,

                    'psychology_scores' => collect($result->psychology_scores ?? [])->mapWithKeys(function ($score, $packageId) use ($packages) {
                        $package = $packages->firstWhere('id', $packageId);
                        return [$package?->code ?? $packageId => $score];
                    })->toArray(),
                ];

                return '
                    <button type="button"
                        onclick=\'openDetailModal(' . json_encode($detail, JSON_HEX_APOS | JSON_HEX_QUOT) . ')\'
                        class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 text-[11px] font-bold transition">
                        <i class="fa-solid fa-eye"></i>
                        Detail
                    </button>
                ';
            })
            ->rawColumns([
                'student_info',
                'status_tes',
                'rekomendasi',
                'final',
                'kelas',
                'aksi',
            ])
            ->toJson();
    }

    public function manualUpdate(Request $request)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'final_package_id' => ['required', 'exists:packages,id'],
            'class_group_id' => ['required', 'exists:class_groups,id'],
        ]);

        DB::transaction(function () use ($validated) {
            $classGroup = ClassGroup::findOrFail($validated['class_group_id']);

            if ($classGroup->students()->count() >= $classGroup->capacity) {
                abort(422, 'Kapasitas kelas sudah penuh.');
            }

            TestResult::where('student_id', $validated['student_id'])->update([
                'final_package_id' => $validated['final_package_id'],
            ]);

            ClassStudent::updateOrCreate(
                ['student_id' => $validated['student_id']],
                [
                    'class_group_id' => $validated['class_group_id'],
                    'package_id' => $validated['final_package_id'],
                    'is_manual_override' => true,
                ]
            );
        });

        return back()->with('success', 'Penempatan siswa berhasil diubah manual.');
    }

    public function export()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="hasil_tes.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Nama',
                'NISN',
                'Kelas Asal',
                'Status Tes',
                'Rekomendasi Instrumen Peminatan',
                'Jurusan Final',
                'Pilihan 1',
                'Pilihan 2',
                'Rencana Setelah Lulus',
            ]);

            TestResult::where('owner_id', auth()->id())
                ->with([
                    'student.packageChoice.firstPackage',
                    'student.packageChoice.secondPackage',
                    'recommendedPackage',
                    'finalPackage',
                ])->chunk(100, function ($results) use ($file) {
                foreach ($results as $result) {
                    fputcsv($file, [
                        $result->student?->name,
                        $result->student?->nisn,
                        $result->student?->origin_class,
                        'Selesai',
                        $result->recommendedPackage?->name,
                        $result->finalPackage?->name,
                        $result->student?->packageChoice?->firstPackage?->name,
                        $result->student?->packageChoice?->secondPackage?->name,
                        $result->student?->packageChoice?->post_graduation_plan,
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
