<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class ExamMonitoringController extends Controller
{
    public function index()
    {
        $academicDuration = Setting::getInt('academic_duration_minutes', 60);
        $psychologyDuration = Setting::getInt('psychology_duration_minutes', 45);

        $students = DB::table('student_test_sessions as sts')
            ->join('students as s', 's.id', '=', 'sts.student_id')
            ->join('test_sessions as ts', 'ts.id', '=', 'sts.test_session_id')
            ->leftJoin('violations as v', function ($join) {
                $join->on('v.student_id', '=', 's.id')
                    ->on('v.test_session_id', '=', 'ts.id');
            })
            ->whereIn('s.status', ['academic_test', 'psychology_test'])
            ->where('sts.status', 'in_progress')
            ->whereNull('sts.finished_at')
            ->selectRaw("
                s.id as student_id,
                s.name,
                s.nisn,
                s.origin_class,
                s.status as student_status,
                ts.id as session_id,
                ts.name as session_name,
                ts.test_date,
                ts.start_time,
                ts.end_time,
                ts.test_type,
                sts.started_at,
                sts.academic_started_at,
                sts.psychology_started_at,
                sts.academic_violation_count,
                sts.psychology_violation_count,
                MAX(v.occurred_at) as last_violation_at
            ")
            ->groupBy([
                's.id',
                's.name',
                's.nisn',
                's.origin_class',
                's.status',
                'ts.id',
                'ts.name',
                'ts.test_date',
                'ts.start_time',
                'ts.end_time',
                'ts.test_type',
                'sts.started_at',
                'sts.academic_started_at',
                'sts.psychology_started_at',
                'sts.academic_violation_count',
                'sts.psychology_violation_count',
            ])
            ->orderBy('ts.test_date')
            ->orderBy('ts.start_time')
            ->orderBy('s.name')
            ->get()
            ->map(function ($student) use ($academicDuration, $psychologyDuration) {
                $isAcademic = $student->student_status === 'academic_test';
                $durationMinutes = $isAcademic ? $academicDuration : $psychologyDuration;
                $startedAt = $isAcademic ? $student->academic_started_at : $student->psychology_started_at;
                $violationCount = $isAcademic
                    ? (int) $student->academic_violation_count
                    : (int) $student->psychology_violation_count;

                $remainingSeconds = $startedAt
                    ? max(0, ($durationMinutes * 60) - now()->diffInSeconds($startedAt))
                    : $durationMinutes * 60;

                $student->active_exam = $isAcademic ? 'Tes Akademik' : 'Tes Psikologi';
                $student->duration_minutes = $durationMinutes;
                $student->violation_count = $violationCount;
                $student->remaining_seconds = $remainingSeconds;
                $student->remaining_label = sprintf('%02d:%02d', floor($remainingSeconds / 60), $remainingSeconds % 60);
                $student->progress_percent = min(100, max(0, round((($durationMinutes * 60) - $remainingSeconds) / max(1, ($durationMinutes * 60)) * 100)));

                return $student;
            });

        $summary = [
            'active_students' => $students->count(),
            'academic' => $students->where('student_status', 'academic_test')->count(),
            'psychology' => $students->where('student_status', 'psychology_test')->count(),
            'high_violation' => $students->where('violation_count', '>=', Setting::getInt('cbt_auto_submit_violation_limit', 3))->count(),
        ];

        return view('admin.exam-monitoring.index', compact('students', 'summary'));
    }
}
