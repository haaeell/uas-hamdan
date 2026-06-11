<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOwner;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use BelongsToOwner;

    protected $fillable = [
        'owner_id',
        'user_id',
        'module',
        'action',
        'subject_type',
        'subject_id',
        'properties',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subject()
    {
        return $this->morphTo();
    }
}
