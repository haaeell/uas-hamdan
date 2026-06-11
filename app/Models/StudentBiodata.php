<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOwner;
use Illuminate\Database\Eloquent\Model;

class StudentBiodata extends Model
{
    use BelongsToOwner;

    protected $fillable = [
        'owner_id',
        'student_id',
        'birth_place',
        'birth_date',
        'gender',
        'address',
        'phone',
        'father_name',
        'mother_name',
        'parent_phone',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
