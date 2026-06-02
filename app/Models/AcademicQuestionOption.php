<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use App\Services\MathTextService;

class AcademicQuestionOption extends Model
{
    protected $fillable = [
        'academic_question_id',
        'label',
        'option_text',
        'is_correct',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    protected static function booted(): void
    {
        $flush = fn () => Cache::store('file')->forget('exam.questions.academic.active.v1');

        static::saved($flush);
        static::deleted($flush);
    }

    public function question()
    {
        return $this->belongsTo(AcademicQuestion::class, 'academic_question_id');
    }

    public function getRenderedOptionTextAttribute(): string
    {
        return app(MathTextService::class)->render($this->option_text);
    }
}
