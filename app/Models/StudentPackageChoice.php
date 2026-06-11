<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOwner;
use Illuminate\Database\Eloquent\Model;

class StudentPackageChoice extends Model
{
    use BelongsToOwner;

    protected $fillable = [
        'owner_id',
        'student_id',
        'first_package_id',
        'second_package_id',
        'post_graduation_plan',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function firstPackage()
    {
        return $this->belongsTo(Package::class, 'first_package_id');
    }

    public function secondPackage()
    {
        return $this->belongsTo(Package::class, 'second_package_id');
    }
}
