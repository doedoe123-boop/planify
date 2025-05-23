<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Feature extends Model
{
    protected $fillable = [
        'name',
        'description',
        'estimated_hours',
        'business_value',
        'is_custom',
    ];

    protected $casts = [
        'is_custom' => 'boolean',
    ];

    public function websiteTypes(): BelongsToMany
    {
        return $this->belongsToMany(WebsiteType::class)
            ->withTimestamps();
    }
    
    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class)
            ->withTimestamps();
    }
}
