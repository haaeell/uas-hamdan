<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOwner;
use Illuminate\Database\Eloquent\Model;

class StudentPsychologyAnswer extends Model
{
    use BelongsToOwner;

    public $timestamps = false;

    protected $fillable = [
        'owner_id',
        'student_id',
        'psychology_question_id',
        'psychology_question_option_id',
        'answered_at',
    ];

    protected $casts = [
        'answered_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function question()
    {
        return $this->belongsTo(PsychologyQuestion::class, 'psychology_question_id');
    }

    public function option()
    {
        return $this->belongsTo(PsychologyQuestionOption::class, 'psychology_question_option_id');
    }
}
