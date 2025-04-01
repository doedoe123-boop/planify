<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_name',
        'project_description',
        'website_type_id',
        'hourly_rate',
        'total_hours',
        'total_cost',
        'custom_features'
    ];

    protected $casts = [
        'custom_features' => 'array',
        'hourly_rate' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'total_hours' => 'integer'
    ];

    protected $attributes = [
        'total_hours' => 0,
        'total_cost' => 0
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function website_type(): BelongsTo
    {
        return $this->belongsTo(WebsiteType::class);
    }

    public function selected_features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class, 'quote_features')
            ->withTimestamps();
    }
} 