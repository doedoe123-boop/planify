<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Feature;
use App\Models\Task;

class BusinessValueAndDeliverablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update features with business values
        $this->updateFeatureBusinessValues();
        
        // Mark selected tasks as deliverables
        $this->markTasksAsDeliverables();
    }
    
    /**
     * Update features with business value statements
     */
    private function updateFeatureBusinessValues(): void
    {
        $featureValues = [
            'Responsive Design' => 'Reach more customers across all devices and improve user experience, leading to higher conversion rates',
            'Content Management System' => 'Empower your team to update content without technical help, reducing maintenance costs',
            'User Authentication' => 'Create personalized experiences that increase customer engagement and loyalty',
            'Contact Forms' => 'Generate leads directly from your website by making it easy for prospects to reach you',
            'Blog Section' => 'Improve SEO ranking and establish thought leadership in your industry',
            'Payment Gateway' => 'Increase revenue by accepting payments securely 24/7',
            'Product Catalog' => 'Showcase your products professionally to drive more sales',
            'Shopping Cart' => 'Provide a seamless shopping experience that converts browsers into buyers',
            'Analytics Integration' => 'Make data-driven decisions by understanding how users interact with your site',
        ];
        
        foreach ($featureValues as $featureName => $businessValue) {
            Feature::where('name', $featureName)->update(['business_value' => $businessValue]);
        }
    }
    
    /**
     * Mark selected tasks as deliverables
     */
    private function markTasksAsDeliverables(): void
    {
        // Mark specific tasks as deliverables
        $deliverableTasks = [
            'Mobile Layout Design',
            'Admin Panel Setup',
            'Content Editor Interface',
            'Login System',
            'Registration Flow',
            'Product Listing UI',
            'Product Detail Pages',
            'Responsive Testing',
            'Deployment'
        ];
        
        foreach ($deliverableTasks as $taskName) {
            Task::where('name', $taskName)->update(['is_deliverable' => true]);
        }
    }
}
