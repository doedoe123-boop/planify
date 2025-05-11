<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\Feature;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define common tasks
        $commonTasks = [
            [
                'name' => 'Requirement Analysis',
                'description' => 'Analyzing and documenting requirements',
                'estimated_hours' => 2,
                'is_required' => true
            ],
            [
                'name' => 'Design Planning',
                'description' => 'Planning the design approach and structure',
                'estimated_hours' => 3,
                'is_required' => true
            ],
            [
                'name' => 'Testing',
                'description' => 'Quality assurance and testing',
                'estimated_hours' => 2,
                'is_required' => true
            ],
            [
                'name' => 'Deployment',
                'description' => 'Setting up and deploying to production',
                'estimated_hours' => 1,
                'is_required' => true
            ],
        ];
        
        // Create common tasks
        foreach ($commonTasks as $taskData) {
            Task::create($taskData);
        }
        
        // Feature specific tasks
        
        // Responsive Design tasks
        $responsiveDesign = Feature::where('name', 'Responsive Design')->first();
        if ($responsiveDesign) {
            $tasks = [
                [
                    'name' => 'Mobile Layout Design',
                    'description' => 'Creating mobile-first layouts',
                    'estimated_hours' => 3,
                ],
                [
                    'name' => 'CSS Media Queries',
                    'description' => 'Implementing responsive CSS breakpoints',
                    'estimated_hours' => 2,
                ],
                [
                    'name' => 'Responsive Testing',
                    'description' => 'Testing on multiple devices and screen sizes',
                    'estimated_hours' => 2,
                ],
            ];
            
            foreach ($tasks as $taskData) {
                $task = Task::create($taskData);
                $responsiveDesign->tasks()->attach($task->id);
            }
        }
        
        // Content Management System tasks
        $cms = Feature::where('name', 'Content Management System')->first();
        if ($cms) {
            $tasks = [
                [
                    'name' => 'Admin Panel Setup',
                    'description' => 'Setting up the administrative interface',
                    'estimated_hours' => 4,
                ],
                [
                    'name' => 'Content Modeling',
                    'description' => 'Designing content types and relationships',
                    'estimated_hours' => 3,
                ],
                [
                    'name' => 'User Roles & Permissions',
                    'description' => 'Configuring access control for content editing',
                    'estimated_hours' => 3,
                ],
                [
                    'name' => 'Content Editor Interface',
                    'description' => 'Building the WYSIWYG editor and content forms',
                    'estimated_hours' => 5,
                ],
            ];
            
            foreach ($tasks as $taskData) {
                $task = Task::create($taskData);
                $cms->tasks()->attach($task->id);
            }
        }
        
        // User Authentication tasks
        $userAuth = Feature::where('name', 'User Authentication')->first();
        if ($userAuth) {
            $tasks = [
                [
                    'name' => 'Login System',
                    'description' => 'Creating secure login functionality',
                    'estimated_hours' => 3,
                ],
                [
                    'name' => 'Registration Flow',
                    'description' => 'Building user registration process',
                    'estimated_hours' => 3,
                ],
                [
                    'name' => 'Password Reset',
                    'description' => 'Implementing password recovery functionality',
                    'estimated_hours' => 2,
                ],
                [
                    'name' => 'Profile Management',
                    'description' => 'User profile editing capabilities',
                    'estimated_hours' => 2,
                ],
                [
                    'name' => 'Session Management',
                    'description' => 'Handling user sessions securely',
                    'estimated_hours' => 2,
                ],
            ];
            
            foreach ($tasks as $taskData) {
                $task = Task::create($taskData);
                $userAuth->tasks()->attach($task->id);
            }
        }
        
        // E-commerce Features
        $productCatalog = Feature::where('name', 'Product Catalog')->first();
        if ($productCatalog) {
            $tasks = [
                [
                    'name' => 'Product Data Modeling',
                    'description' => 'Setting up product data structure',
                    'estimated_hours' => 3,
                ],
                [
                    'name' => 'Category Management',
                    'description' => 'Creating category hierarchy system',
                    'estimated_hours' => 3,
                ],
                [
                    'name' => 'Product Listing UI',
                    'description' => 'Building product list and grid views',
                    'estimated_hours' => 4,
                ],
                [
                    'name' => 'Product Detail Pages',
                    'description' => 'Designing and implementing product details',
                    'estimated_hours' => 4,
                ],
                [
                    'name' => 'Product Search & Filtering',
                    'description' => 'Creating search and filter functionality',
                    'estimated_hours' => 6,
                ],
            ];
            
            foreach ($tasks as $taskData) {
                $task = Task::create($taskData);
                $productCatalog->tasks()->attach($task->id);
            }
        }
        
        // Add tasks for more features as needed...
    }
}
