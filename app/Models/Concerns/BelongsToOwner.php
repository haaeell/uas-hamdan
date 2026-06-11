<?php

namespace App\Models\Concerns;

use App\Support\OwnerContext;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToOwner
{
    protected static function bootBelongsToOwner(): void
    {
        static::addGlobalScope('owner', function (Builder $builder) {
            $ownerId = OwnerContext::id();

            if ($ownerId) {
                $builder->where($builder->getModel()->getTable() . '.owner_id', $ownerId);
            }
        });

        static::creating(function ($model) {
            if (!$model->owner_id && ($ownerId = OwnerContext::id())) {
                $model->owner_id = $ownerId;
            }
        });
    }

    public function owner()
    {
        return $this->belongsTo(\App\Models\User::class, 'owner_id');
    }
}
