<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class WebsiteType extends Model
{
    protected $fillable = [
        'name',
        'description',
        'base_hours',
    ];

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class)
            ->withTimestamps();
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }
} 