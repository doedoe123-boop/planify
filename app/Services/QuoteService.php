<?php

namespace App\Services;

use App\Models\Quote;
use App\Models\WebsiteType;
use App\Models\Feature;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class QuoteService
{
    /**
     * Create a new quote with all associated data
     * 
     * @param array $data
     * @return Quote
     */
    public function createQuote(array $data)
    {
        // Start a database transaction
        return DB::transaction(function() use ($data) {
            $websiteType = WebsiteType::findOrFail($data['website_type_id']);
            
            // Generate solution overview if not provided
            $solutionOverview = $data['solution_overview'] ?? $this->generateSolutionOverview(
                $data['project_description'], 
                $data['business_goals'] ?? null,
                $websiteType->name
            );
            
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

            // Generate business value points if not provided
            $businessValuePoints = $data['business_value_points'] ?? $this->generateBusinessValuePoints(
                $data['selected_features'] ?? [],
                $websiteType,
                $data['industry'] ?? null
            );

            // Create the quote with calculated values
            $quote = Quote::create([
                'user_id' => Auth::id(),
                'website_type_id' => $data['website_type_id'],
                'project_name' => $data['project_name'],
                'project_description' => $data['project_description'],
                'industry' => $data['industry'] ?? null,
                'business_goals' => $data['business_goals'] ?? null,
                'solution_overview' => $solutionOverview,
                'business_value_points' => $businessValuePoints,
                'hourly_rate' => $data['hourly_rate'],
                'total_hours' => $totalHours,
                'total_cost' => $totalCost,
                'custom_features' => !empty($data['custom_features']) ? $data['custom_features'] : null
            ]);

            // Attach selected features if any
            if (!empty($data['selected_features'])) {
                $quote->selected_features()->attach($data['selected_features']);
                
                // Get tasks for the selected features
                $selectedFeatures = Feature::with('tasks')->whereIn('id', $data['selected_features'])->get();
                
                // Collect all tasks from the selected features
                $tasksToAttach = [];
                
                foreach ($selectedFeatures as $feature) {
                    foreach ($feature->tasks as $task) {
                        $tasksToAttach[$task->id] = [
                            'included' => true,
                            'custom_hours' => null
                        ];
                    }
                }
                
                // Also include common tasks if there are any
                $commonTasks = Task::where('is_required', true)
                    ->whereDoesntHave('features')
                    ->get();
                    
                foreach ($commonTasks as $task) {
                    $tasksToAttach[$task->id] = [
                        'included' => true,
                        'custom_hours' => null
                    ];
                }
                
                // Attach tasks to the quote
                if (!empty($tasksToAttach)) {
                    $quote->tasks()->attach($tasksToAttach);
                }
            }

            return $quote->load(['website_type', 'selected_features', 'tasks']);
        });
    }

    /**
     * Get quotes for the authenticated user
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserQuotes()
    {
        return Quote::with('website_type')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
    }

    /**
     * Get a specific quote with its relationships
     * 
     * @param Quote $quote
     * @return Quote
     */
    public function getQuote(Quote $quote)
    {
        if ($quote->user_id !== Auth::id()) {
            abort(403);
        }

        return $quote->load(['website_type', 'selected_features', 'tasks']);
    }

    /**
     * Get website types with their features
     * 
     * @return array
     */
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
                            'business_value' => $feature->business_value,
                        ];
                    }),
                ];
            });
    }
    
    /**
     * Get all tasks for a specific quote, grouped by feature
     * 
     * @param Quote $quote
     * @return array
     */
    public function getTasksBreakdown(Quote $quote)
    {
        // Get all selected features with their tasks
        $features = $quote->selected_features()->with('tasks')->get();
        
        // Get quote tasks with pivot information
        $quoteTasks = $quote->tasks()->with('features')->get();
        
        // Group tasks by feature
        $tasksByFeature = [];
        
        // Add common tasks (those not associated with specific features)
        $commonTasks = $quoteTasks->filter(function ($task) {
            return $task->features->isEmpty();
        });
        
        if ($commonTasks->isNotEmpty()) {
            $tasksByFeature['Common Tasks'] = $commonTasks->map(function ($task) {
                return [
                    'id' => $task->id,
                    'name' => $task->name,
                    'description' => $task->description,
                    'hours' => $task->pivot->custom_hours ?? $task->estimated_hours,
                    'included' => $task->pivot->included,
                    'is_deliverable' => $task->is_deliverable,
                ];
            })->values()->toArray();
        }
        
        // Add feature-specific tasks
        foreach ($features as $feature) {
            $featureTasks = $quoteTasks->filter(function ($task) use ($feature) {
                return $task->features->contains('id', $feature->id);
            });
            
            if ($featureTasks->isNotEmpty()) {
                $tasksByFeature[$feature->name] = $featureTasks->map(function ($task) {
                    return [
                        'id' => $task->id,
                        'name' => $task->name,
                        'description' => $task->description,
                        'hours' => $task->pivot->custom_hours ?? $task->estimated_hours,
                        'included' => $task->pivot->included,
                        'is_deliverable' => $task->is_deliverable,
                    ];
                })->values()->toArray();
            }
        }
        
        return $tasksByFeature;
    }
    
    /**
     * Update tasks for a quote
     * 
     * @param Quote $quote
     * @param array $tasks Array of task updates [id => ['included' => bool, 'custom_hours' => float|null]]
     * @return Quote
     */
    public function updateQuoteTasks(Quote $quote, array $tasks)
    {
        foreach ($tasks as $taskId => $taskData) {
            $quote->tasks()->updateExistingPivot($taskId, [
                'included' => $taskData['included'] ?? true,
                'custom_hours' => $taskData['custom_hours'] ?? null
            ]);
        }
        
        // Recalculate quote totals
        $this->recalculateQuoteTotals($quote);
        
        return $quote->fresh(['website_type', 'selected_features', 'tasks']);
    }
    
    /**
     * Get suggested features based on project description and industry
     * 
     * @param string $projectDescription
     * @param string|null $industry
     * @return array
     */
    public function getSuggestedFeatures($projectDescription, $industry = null)
    {
        // Get all available features
        $features = Feature::all();
        
        $suggestedFeatures = [];
        
        // Simple keyword matching for now (could be enhanced with ML/AI in the future)
        foreach ($features as $feature) {
            $score = 0;
            
            // Check if feature name or description appears in project description
            if (Str::contains(strtolower($projectDescription), strtolower($feature->name))) {
                $score += 5;
            }
            
            $descriptionWords = explode(' ', strtolower($feature->description));
            foreach ($descriptionWords as $word) {
                if (strlen($word) > 3 && Str::contains(strtolower($projectDescription), $word)) {
                    $score += 1;
                }
            }
            
            // Add industry-specific boost
            if ($industry && $this->isFeatureRelevantForIndustry($feature, $industry)) {
                $score += 3;
            }
            
            if ($score > 0) {
                $suggestedFeatures[] = [
                    'id' => $feature->id,
                    'name' => $feature->name,
                    'score' => $score
                ];
            }
        }
        
        // Sort by relevance score
        usort($suggestedFeatures, function($a, $b) {
            return $b['score'] - $a['score'];
        });
        
        // Return top 5 suggestions
        return array_slice($suggestedFeatures, 0, 5);
    }
    
    /**
     * Check if a feature is relevant for a specific industry
     * 
     * @param Feature $feature
     * @param string $industry
     * @return bool
     */
    private function isFeatureRelevantForIndustry($feature, $industry)
    {
        // Map industry to commonly used features
        $industryFeatureMap = [
            'ecommerce' => ['Shopping Cart', 'Product Catalog', 'Payment Gateway'],
            'healthcare' => ['User Authentication', 'Content Management System', 'HIPAA Compliance'],
            'education' => ['User Authentication', 'Content Management System', 'Learning Management'],
            'real-estate' => ['Property Listings', 'Content Management System', 'Maps Integration'],
            'finance' => ['User Authentication', 'Secure Portal', 'Payment Gateway', 'Compliance'],
        ];
        
        $industry = strtolower($industry);
        
        if (isset($industryFeatureMap[$industry])) {
            return in_array($feature->name, $industryFeatureMap[$industry]);
        }
        
        return false;
    }
    
    /**
     * Generate a solution overview based on project info
     * 
     * @param string $projectDescription
     * @param string|null $businessGoals
     * @param string $websiteType
     * @return string
     */
    private function generateSolutionOverview($projectDescription, $businessGoals, $websiteType)
    {
        $overview = "We propose a custom $websiteType solution";
        
        if ($businessGoals) {
            $overview .= " designed to help you $businessGoals";
        }
        
        $overview .= ". Based on your project requirements, we'll build a professional, user-friendly platform";
        
        // Add more specific details based on website type
        switch ($websiteType) {
            case 'E-commerce Website':
                $overview .= " that provides a seamless shopping experience, secure payment processing, and easy product management";
                break;
            case 'Corporate Website':
                $overview .= " that showcases your brand identity, services, and expertise to strengthen your online presence";
                break;
            case 'Portfolio':
                $overview .= " that displays your work in an engaging, professional manner to attract clients and demonstrate your capabilities";
                break;
            case 'Blog/Content Site':
                $overview .= " that delivers your content through an attractive, easy-to-navigate interface to grow your audience and engagement";
                break;
            case 'Custom Web App':
                $overview .= " with custom functionality tailored to your specific business needs and processes";
                break;
        }
        
        $overview .= ".";
        
        return $overview;
    }
    
    /**
     * Generate business value points based on selected features
     * 
     * @param array $selectedFeatureIds
     * @param WebsiteType $websiteType
     * @param string|null $industry
     * @return array
     */
    private function generateBusinessValuePoints($selectedFeatureIds, $websiteType, $industry)
    {
        $valuePoints = [];
        
        // Add website type specific value point
        switch ($websiteType->name) {
            case 'E-commerce Website':
                $valuePoints[] = "Increase revenue through a professional online store that operates 24/7";
                break;
            case 'Corporate Website':
                $valuePoints[] = "Strengthen your brand image with a professional online presence";
                break;
            case 'Portfolio':
                $valuePoints[] = "Showcase your work to attract more clients and opportunities";
                break;
            case 'Blog/Content Site':
                $valuePoints[] = "Build an engaged audience through regular content updates";
                break;
            case 'Custom Web App':
                $valuePoints[] = "Improve operational efficiency with custom software tailored to your needs";
                break;
            default:
                $valuePoints[] = "Enhance your online presence with a professional website";
        }
        
        // Add industry-specific value point
        if ($industry) {
            switch (strtolower($industry)) {
                case 'ecommerce':
                    $valuePoints[] = "Compete effectively in the digital marketplace with a full-featured online store";
                    break;
                case 'healthcare':
                    $valuePoints[] = "Build trust with patients through a professional, accessible online platform";
                    break;
                case 'education':
                    $valuePoints[] = "Enhance learning experiences with modern digital tools and resources";
                    break;
                case 'real-estate':
                    $valuePoints[] = "Showcase properties effectively to attract qualified buyers and tenants";
                    break;
                case 'finance':
                    $valuePoints[] = "Provide secure, convenient financial services to your clients online";
                    break;
            }
        }
        
        // Add feature-specific value points
        if (!empty($selectedFeatureIds)) {
            $features = Feature::whereIn('id', $selectedFeatureIds)->get();
            
            foreach ($features as $feature) {
                if ($feature->business_value) {
                    $valuePoints[] = $feature->business_value;
                } else {
                    // Generate generic business value for common features
                    switch ($feature->name) {
                        case 'Responsive Design':
                            $valuePoints[] = "Reach customers on all devices with a mobile-friendly design";
                            break;
                        case 'Content Management System':
                            $valuePoints[] = "Update your website content easily without technical knowledge";
                            break;
                        case 'User Authentication':
                            $valuePoints[] = "Create personalized experiences for registered users";
                            break;
                        case 'Payment Gateway':
                            $valuePoints[] = "Accept payments securely online";
                            break;
                    }
                }
            }
        }
        
        // Limit to 5 value points and ensure uniqueness
        return array_slice(array_unique($valuePoints), 0, 5);
    }
    
    /**
     * Get deliverables list from tasks
     * 
     * @param Quote $quote
     * @return array
     */
    public function getDeliverables(Quote $quote)
    {
        $deliverables = [];
        
        // Get all tasks that are marked as deliverables and are included in the quote
        $tasks = $quote->tasks()
            ->where('is_deliverable', true)
            ->wherePivot('included', true)
            ->get();
            
        foreach ($tasks as $task) {
            $deliverables[] = [
                'name' => $task->name,
                'description' => $task->description
            ];
        }
        
        // Add website type as a deliverable
        $deliverables[] = [
            'name' => $quote->website_type->name,
            'description' => $quote->website_type->description
        ];
        
        // Add generic deliverables based on website type
        switch ($quote->website_type->name) {
            case 'E-commerce Website':
                $deliverables[] = [
                    'name' => 'Online Store',
                    'description' => 'A fully functional e-commerce website with product listings and checkout'
                ];
                break;
            case 'Corporate Website':
                $deliverables[] = [
                    'name' => 'Business Website',
                    'description' => 'A professional business website representing your brand and services'
                ];
                break;
        }
        
        return $deliverables;
    }
    
    /**
     * Recalculate quote total hours and cost
     * 
     * @param Quote $quote
     * @return void
     */
    private function recalculateQuoteTotals(Quote $quote)
    {
        // Get base hours from website type
        $totalHours = $quote->website_type->base_hours;
        
        // Add hours from included tasks (using custom hours if set)
        $quote->load('tasks');
        foreach ($quote->tasks as $task) {
            if ($task->pivot->included) {
                $taskHours = $task->pivot->custom_hours ?? $task->estimated_hours;
                $totalHours += $taskHours;
            }
        }
        
        // Add hours from custom features
        if (!empty($quote->custom_features)) {
            $totalHours += collect($quote->custom_features)->sum('hours');
        }
        
        // Calculate total cost
        $totalCost = $totalHours * $quote->hourly_rate;
        
        // Update quote
        $quote->update([
            'total_hours' => $totalHours,
            'total_cost' => $totalCost
        ]);
    }
}