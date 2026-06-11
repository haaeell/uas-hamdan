<?php

namespace App\Http\Middleware;

use App\Models\TestSession;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class EnsureStudentTestSessionIsOpen
{
    public function handle(Request $request, Closure $next): Response
    {
        $student = auth()->user()?->student;

        abort_if(!$student, 403);

        $session = TestSession::activeForOriginClass($student->origin_class);

        if (!$session) {
            return redirect()
                ->route('siswa.waiting-session')
                ->with('warning', 'Sesi tes Anda belum dibuka atau sudah berakhir.');
        }

        $examType = 'psychology';

        if (!in_array($session->test_type, [$examType, 'both'], true)) {
            return redirect()
                ->route('siswa.waiting-session')
                ->with('warning', 'Jenis tes pada sesi ini belum tersedia untuk Anda.');
        }

        $now = now();

        $pivot = DB::table('student_test_sessions')
            ->where('student_id', $student->id)
            ->where('test_session_id', $session->id)
            ->first();

        if (!$pivot) {
            DB::table('student_test_sessions')->insert([
                'student_id' => $student->id,
                'test_session_id' => $session->id,
                'started_at' => $now,
                'academic_started_at' => null,
                'psychology_started_at' => $now,
                'status' => 'in_progress',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $pivot = (object) [
                'student_id' => $student->id,
                'test_session_id' => $session->id,
                'started_at' => $now,
                'academic_started_at' => null,
                'psychology_started_at' => $now,
                'academic_submitted_at' => null,
                'psychology_submitted_at' => null,
                'academic_violation_count' => 0,
                'psychology_violation_count' => 0,
                'status' => 'in_progress',
            ];
        } else {
            $updates = [
                'status' => 'in_progress',
                'updated_at' => $now,
            ];

            if (!$pivot->started_at) {
                $updates['started_at'] = $now;
            }

            if (!$pivot->psychology_started_at) {
                $updates['psychology_started_at'] = $now;
            }

            DB::table('student_test_sessions')
                ->where('student_id', $student->id)
                ->where('test_session_id', $session->id)
                ->update($updates);

            foreach ($updates as $key => $value) {
                $pivot->{$key} = $value;
            }
        }

        $request->attributes->set('active_test_session_id', $session->id);
        $request->attributes->set('active_test_session_state', $pivot);

        if ($student->status === 'waiting_session') {
            $student->update(['status' => 'psychology_test']);
        }

        return $next($request);
    }
}
