<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'owner_id',
        'name',
        'nisn',
        'email',
        'password',
        'role',
        'is_active',
        'approved_at',
        'login_magic_token_hash',
        'login_magic_token_expires_at',
        'email_otp_code_hash',
        'email_otp_expires_at',
        'exam_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'approved_at' => 'datetime',
        'login_magic_token_expires_at' => 'datetime',
        'email_otp_expires_at' => 'datetime',
    ];

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function ownedStudents()
    {
        return $this->hasMany(Student::class, 'owner_id');
    }

    public function publishedAnnouncements()
    {
        return $this->hasMany(Announcement::class, 'published_by');
    }

    public function reviewedObjections()
    {
        return $this->hasMany(Objection::class, 'reviewed_by');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }
}
