<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOwner;
use Illuminate\Database\Eloquent\Model;

class ClassGroup extends Model
{
    use BelongsToOwner;

    protected $fillable = [
        'owner_id',
        'package_id',
        'name',
        'capacity',
        'is_locked',
    ];

    protected $casts = [
        'is_locked' => 'boolean',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function students()
    {
        return $this->hasMany(ClassStudent::class);
    }
}
