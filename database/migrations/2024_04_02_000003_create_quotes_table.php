<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('website_type_id')->constrained()->onDelete('cascade');
            $table->string('project_name');
            $table->text('project_description')->nullable();
            $table->decimal('hourly_rate', 10, 2);
            $table->integer('total_hours')->default(0);
            $table->decimal('total_cost', 10, 2)->default(0);
            $table->json('custom_features')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('quotes');
    }
}; 