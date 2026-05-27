<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Violation extends Model
{
    protected $fillable = [
        'student_id',
        'test_session_id',
        'exam_type',
        'action',
        'violation_count',
        'ip_address',
        'user_agent',
        'device_info',
        'occurred_at',
    ];

    protected $casts = [
        'device_info' => 'array',
        'occurred_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function testSession()
    {
        return $this->belongsTo(TestSession::class);
    }
}
