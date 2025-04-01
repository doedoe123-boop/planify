<?php

namespace Database\Seeders;

use App\Models\Feature;
use App\Models\WebsiteType;
use Illuminate\Database\Seeder;

class WebsiteTypeSeeder extends Seeder
{
    public function run()
    {
        // Create Website Types
        $corporateWebsite = WebsiteType::create([
            'name' => 'Corporate Website',
            'description' => 'Professional website for businesses and organizations',
            'base_hours' => 40,
        ]);

        $ecommerceWebsite = WebsiteType::create([
            'name' => 'E-commerce Website',
            'description' => 'Online store with product catalog and shopping cart',
            'base_hours' => 80,
        ]);

        $landingPage = WebsiteType::create([
            'name' => 'Landing Page',
            'description' => 'Single page website focused on conversion',
            'base_hours' => 20,
        ]);

        $portfolio = WebsiteType::create([
            'name' => 'Portfolio',
            'description' => 'Showcase your work and projects',
            'base_hours' => 30,
        ]);

        $blog = WebsiteType::create([
            'name' => 'Blog/Content Site',
            'description' => 'Content-focused website with regular updates',
            'base_hours' => 35,
        ]);

        $customWebApp = WebsiteType::create([
            'name' => 'Custom Web App',
            'description' => 'Custom web application with specific functionality',
            'base_hours' => 100,
        ]);

        // Create Features
        $features = [
            // Common Features
            $responsiveDesign = Feature::create([
                'name' => 'Responsive Design',
                'description' => 'Website adapts to all screen sizes',
                'estimated_hours' => 10,
            ]),
            $contentManagement = Feature::create([
                'name' => 'Content Management System',
                'description' => 'Easy content updates without coding',
                'estimated_hours' => 15,
            ]),
            $contactForms = Feature::create([
                'name' => 'Contact Forms',
                'description' => 'Custom contact and inquiry forms',
                'estimated_hours' => 5,
            ]),

            // E-commerce Features
            $productCatalog = Feature::create([
                'name' => 'Product Catalog',
                'description' => 'Organized product listings with categories',
                'estimated_hours' => 20,
            ]),
            $shoppingCart = Feature::create([
                'name' => 'Shopping Cart',
                'description' => 'Full shopping cart functionality',
                'estimated_hours' => 25,
            ]),
            $paymentGateway = Feature::create([
                'name' => 'Payment Gateway',
                'description' => 'Secure payment processing',
                'estimated_hours' => 15,
            ]),

            // Blog Features
            $blogSection = Feature::create([
                'name' => 'Blog Section',
                'description' => 'Full blog functionality with categories and tags',
                'estimated_hours' => 10,
            ]),
            $comments = Feature::create([
                'name' => 'Comments System',
                'description' => 'User comments and moderation',
                'estimated_hours' => 8,
            ]),

            // Additional Features
            $userAuth = Feature::create([
                'name' => 'User Authentication',
                'description' => 'User registration and login system',
                'estimated_hours' => 12,
            ]),
            $analytics = Feature::create([
                'name' => 'Analytics Integration',
                'description' => 'Track website performance and user behavior',
                'estimated_hours' => 4,
            ]),
        ];

        // Attach Features to Website Types
        $corporateWebsite->features()->attach([
            $responsiveDesign->id,
            $contentManagement->id,
            $contactForms->id,
            $blogSection->id,
            $analytics->id,
        ]);

        $ecommerceWebsite->features()->attach([
            $responsiveDesign->id,
            $contentManagement->id,
            $productCatalog->id,
            $shoppingCart->id,
            $paymentGateway->id,
            $userAuth->id,
            $analytics->id,
        ]);

        $landingPage->features()->attach([
            $responsiveDesign->id,
            $contactForms->id,
            $analytics->id,
        ]);

        $portfolio->features()->attach([
            $responsiveDesign->id,
            $contentManagement->id,
            $contactForms->id,
            $analytics->id,
        ]);

        $blog->features()->attach([
            $responsiveDesign->id,
            $contentManagement->id,
            $blogSection->id,
            $comments->id,
            $userAuth->id,
            $analytics->id,
        ]);

        $customWebApp->features()->attach([
            $responsiveDesign->id,
            $contentManagement->id,
            $userAuth->id,
            $analytics->id,
        ]);
    }
} 