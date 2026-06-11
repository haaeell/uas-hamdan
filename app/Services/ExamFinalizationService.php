<?php

namespace App\Services;

use App\Models\Student;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ExamFinalizationService
{
    public function __construct(private PsychologyScoringService $psychologyScoringService)
    {
    }

    public function finalizePsychology(Student $student, int $sessionId, string $submitType = 'manual', ?int $durationLimitSeconds = null): void
    {
        DB::transaction(function () use ($student, $sessionId, $submitType, $durationLimitSeconds) {
            $this->psychologyScoringService->calculate($student);

            $sessionState = DB::table('student_test_sessions')
                ->where('student_id', $student->id)
                ->where('test_session_id', $sessionId)
                ->first();

            if (!$sessionState || $sessionState->psychology_submitted_at) {
                return;
            }

            $submittedAt = now();
            $durationSeconds = $this->durationSeconds($sessionState->psychology_started_at, $submittedAt, $durationLimitSeconds);

            DB::table('student_test_sessions')
                ->where('student_id', $student->id)
                ->where('test_session_id', $sessionId)
                ->whereNull('psychology_submitted_at')
                ->update([
                    'psychology_submitted_at' => $submittedAt,
                    'psychology_duration_seconds' => $durationSeconds,
                    'psychology_submit_type' => $this->normalizeSubmitType($submitType),
                    'status' => 'finished',
                    'finished_at' => $submittedAt,
                    'updated_at' => $submittedAt,
                ]);

            $student->update(['status' => 'completed']);
        });
    }

    private function durationSeconds(?string $startedAt, Carbon $submittedAt, ?int $limitSeconds): ?int
    {
        if (!$startedAt) {
            return null;
        }

        $durationSeconds = max(0, Carbon::parse($startedAt)->diffInSeconds($submittedAt));

        return $limitSeconds !== null ? min($durationSeconds, $limitSeconds) : $durationSeconds;
    }

    private function normalizeSubmitType(?string $submitType): string
    {
        return in_array($submitType, ['manual', 'timeout', 'violation'], true)
            ? $submitType
            : 'manual';
    }
}
