<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOwner;
use Illuminate\Database\Eloquent\Model;

class ClassStudent extends Model
{
    use BelongsToOwner;

    protected $fillable = [
        'owner_id',
        'class_group_id',
        'student_id',
        'package_id',
        'is_manual_override',
    ];

    protected $casts = [
        'is_manual_override' => 'boolean',
    ];

    public function classGroup()
    {
        return $this->belongsTo(ClassGroup::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
