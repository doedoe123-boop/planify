<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Task extends Model
{
    protected $fillable = [
        'name',
        'description',
        'estimated_hours',
        'is_required',
        'is_deliverable'
    ];

    protected $casts = [
        'estimated_hours' => 'float',
        'is_required' => 'boolean',
        'is_deliverable' => 'boolean'
    ];

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class)
            ->withTimestamps();
    }
}
