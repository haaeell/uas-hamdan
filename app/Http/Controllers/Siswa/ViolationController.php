<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Violation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ViolationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'exam_type' => ['required', 'in:academic,psychology'],
            'action' => ['required', 'string', 'max:100'],
            'device_info' => ['nullable', 'array'],
        ]);

        $student = auth()->user()->student;
        $sessionId = $request->attributes->get('active_test_session_id');

        abort_if(!$sessionId, 403, 'Sesi tes tidak ditemukan.');

        $column = $validated['exam_type'] === 'academic'
            ? 'academic_violation_count'
            : 'psychology_violation_count';

        $totalViolations = DB::transaction(function () use ($student, $sessionId, $validated, $request, $column) {
            $pivot = DB::table('student_test_sessions')
                ->where('student_id', $student->id)
                ->where('test_session_id', $sessionId)
                ->lockForUpdate()
                ->first();

            abort_if(!$pivot, 403, 'Sesi tes siswa tidak ditemukan.');

            $totalViolations = ((int) ($pivot->{$column} ?? 0)) + 1;

            DB::table('student_test_sessions')
                ->where('student_id', $student->id)
                ->where('test_session_id', $sessionId)
                ->update([
                    $column => $totalViolations,
                    'updated_at' => now(),
                ]);

            Violation::create([
                'student_id' => $student->id,
                'test_session_id' => $sessionId,
                'exam_type' => $validated['exam_type'],
                'action' => $validated['action'],
                'violation_count' => $totalViolations,
                'device_info' => $validated['device_info'] ?? null,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'occurred_at' => now(),
            ]);

            return $totalViolations;
        });

        $limit = \App\Models\Setting::getInt('cbt_auto_submit_violation_limit', 3);

        return response()->json([
            'message' => 'Pelanggaran dicatat.',
            'total_violations' => $totalViolations,
            'auto_submit' => $totalViolations >= $limit,
        ]);
    }
}
