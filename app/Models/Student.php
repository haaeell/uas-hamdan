<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'nisn',
        'nis',
        'name',
        'origin_class',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function biodata()
    {
        return $this->hasOne(StudentBiodata::class);
    }

    public function selfie()
    {
        return $this->hasOne(StudentSelfie::class);
    }

    public function packageChoice()
    {
        return $this->hasOne(StudentPackageChoice::class);
    }

    public function academicAnswers()
    {
        return $this->hasMany(StudentAcademicAnswer::class);
    }

    public function psychologyAnswers()
    {
        return $this->hasMany(StudentPsychologyAnswer::class);
    }

    public function result()
    {
        return $this->hasOne(TestResult::class);
    }

    public function classStudent()
    {
        return $this->hasOne(ClassStudent::class);
    }

    public function testSessions()
    {
        return $this->belongsToMany(TestSession::class, 'student_test_sessions')
            ->withPivot([
                'started_at',
                'finished_at',
                'status',
                'academic_started_at',
                'psychology_started_at',
                'academic_submitted_at',
                'psychology_submitted_at',
                'academic_duration_seconds',
                'psychology_duration_seconds',
                'academic_submit_type',
                'psychology_submit_type',
                'academic_violation_count',
                'psychology_violation_count',
            ])
            ->withTimestamps();
    }

    public function violations()
    {
        return $this->hasMany(Violation::class);
    }

    public function announcementResponses()
    {
        return $this->hasMany(AnnouncementResponse::class);
    }

    public function objections()
    {
        return $this->hasMany(Objection::class);
    }
}
