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
        'industry',
        'project_description',
        'business_goals',
        'solution_overview',
        'website_type_id',
        'hourly_rate',
        'total_hours',
        'total_cost',
        'custom_features',
        'business_value_points'
    ];

    protected $casts = [
        'custom_features' => 'array',
        'business_value_points' => 'array',
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
    
    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'quote_tasks')
            ->withPivot('custom_hours', 'included')
            ->withTimestamps();
    }
} 