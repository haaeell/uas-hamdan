<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\PsychologyQuestion;
use App\Models\Setting;
use App\Models\StudentPsychologyAnswer;
use App\Services\PsychologyScoringService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PsychologyTestController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;

        abort_if($student->status !== 'psychology_test', 403);

        $questions = PsychologyQuestion::where('is_active', true)
            ->with('options')
            ->orderBy('order')
            ->get();

        $answers = $student->psychologyAnswers()
            ->pluck('psychology_question_option_id', 'psychology_question_id')
            ->toArray();

        $cbtSettings = [
            'duration_minutes' => Setting::getInt('psychology_duration_minutes', 45),
            'violation_limit' => Setting::getInt('cbt_auto_submit_violation_limit', 3),
            'force_fullscreen' => Setting::getBool('cbt_force_fullscreen', true),
            'warning_message' => Setting::getSetting('cbt_warning_message', 'Aktivitas mencurigakan terdeteksi dan dicatat.'),
            'student_help_text' => Setting::getSetting('student_help_text', ''),
        ];

        return view('siswa.psychology.index', compact('student', 'questions', 'answers', 'cbtSettings'));
    }

    public function autosave(Request $request)
    {
        $validated = $request->validate([
            'psychology_question_id' => ['required', 'exists:psychology_questions,id'],
            'psychology_question_option_id' => ['required', 'exists:psychology_question_options,id'],
        ]);

        $student = auth()->user()->student;

        StudentPsychologyAnswer::updateOrCreate(
            [
                'student_id' => $student->id,
                'psychology_question_id' => $validated['psychology_question_id'],
            ],
            [
                'psychology_question_option_id' => $validated['psychology_question_option_id'],
                'answered_at' => now(),
            ]
        );

        return response()->json([
            'message' => 'Jawaban tersimpan.',
        ]);
    }

    public function submit(PsychologyScoringService $scoringService)
    {
        $student = auth()->user()->student;

        DB::transaction(function () use ($student, $scoringService) {
            $scoringService->calculate($student);

            $student->update(['status' => 'completed']);

            $student->testSessions()->updateExistingPivot(
                $student->testSessions()->latest()->first()?->id,
                [
                    'status' => 'finished',
                    'finished_at' => now(),
                ]
            );
        });

        return response()->json([
            'message' => 'Tes psikologi selesai.',
            'redirect_url' => route('siswa.announcements.index'),
        ]);
    }
}
