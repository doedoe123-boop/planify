<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->text('business_goals')->nullable()->after('project_description');
            $table->text('solution_overview')->nullable()->after('business_goals');
            $table->json('business_value_points')->nullable()->after('custom_features');
            $table->string('industry')->nullable()->after('project_name');
        });
        
        // Add business_value field to features
        Schema::table('features', function (Blueprint $table) {
            $table->text('business_value')->nullable()->after('estimated_hours');
        });
        
        // Add deliverable field to tasks
        Schema::table('tasks', function (Blueprint $table) {
            $table->boolean('is_deliverable')->default(false)->after('is_required');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn(['business_goals', 'solution_overview', 'business_value_points', 'industry']);
        });
        
        Schema::table('features', function (Blueprint $table) {
            $table->dropColumn('business_value');
        });
        
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('is_deliverable');
        });
    }
};
