<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PsychologyQuestionOption extends Model
{
    protected $fillable = [
        'psychology_question_id',
        'label',
        'option_text',
    ];

    protected static function booted(): void
    {
        $flush = fn () => Cache::store('file')->forget('exam.questions.psychology.active.v1');

        static::saved($flush);
        static::deleted($flush);
    }

    public function question()
    {
        return $this->belongsTo(PsychologyQuestion::class, 'psychology_question_id');
    }

    public function weights()
    {
        return $this->hasMany(PsychologyOptionWeight::class);
    }
}
