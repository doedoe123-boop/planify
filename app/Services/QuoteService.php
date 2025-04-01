<?php

namespace App\Services;

use App\Models\Quote;
use App\Models\WebsiteType;
use App\Models\Feature;
use Illuminate\Support\Facades\Auth;

class QuoteService
{
    public function createQuote(array $data)
    {
        $websiteType = WebsiteType::findOrFail($data['website_type_id']);
        
        // Calculate total hours
        $totalHours = $websiteType->base_hours;

        // Add hours from selected features
        if (!empty($data['selected_features'])) {
            $selectedFeatures = Feature::whereIn('id', $data['selected_features'])->get();
            $totalHours += $selectedFeatures->sum('estimated_hours');
        }

        // Add hours from custom features
        if (!empty($data['custom_features'])) {
            $totalHours += collect($data['custom_features'])->sum('hours');
        }

        // Calculate total cost
        $totalCost = $totalHours * $data['hourly_rate'];

        // Create the quote with calculated values
        $quote = Quote::create([
            'user_id' => auth()->id(),
            'website_type_id' => $data['website_type_id'],
            'project_name' => $data['project_name'],
            'project_description' => $data['project_description'],
            'hourly_rate' => $data['hourly_rate'],
            'total_hours' => $totalHours,
            'total_cost' => $totalCost,
            'custom_features' => !empty($data['custom_features']) ? $data['custom_features'] : null
        ]);

        // Attach selected features if any
        if (!empty($data['selected_features'])) {
            $quote->selected_features()->attach($data['selected_features']);
        }

        return $quote->load(['website_type', 'selected_features']);
    }

    public function getUserQuotes()
    {
        return Quote::with('websiteType')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
    }

    public function getQuote(Quote $quote)
    {
        if ($quote->user_id !== Auth::id()) {
            abort(403);
        }

        return $quote->load('websiteType');
    }

    public function getWebsiteTypesWithFeatures()
    {
        return WebsiteType::with('features')
            ->get()
            ->map(function ($type) {
                return [
                    'id' => $type->id,
                    'name' => $type->name,
                    'description' => $type->description,
                    'base_hours' => $type->base_hours,
                    'features' => $type->features->map(function ($feature) {
                        return [
                            'id' => $feature->id,
                            'name' => $feature->name,
                            'description' => $feature->description,
                            'estimated_hours' => $feature->estimated_hours,
                        ];
                    }),
                ];
            });
    }
} 