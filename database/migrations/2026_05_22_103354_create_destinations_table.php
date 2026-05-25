<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('country');
            $table->string('state')->nullable();
            $table->string('airport_code')->nullable();
            $table->text('short_desc')->nullable();
            $table->longText('description')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('banner_image')->nullable();
            $table->json('gallery')->nullable(); // json store of picture paths
            
            // Fares & Information
            $table->decimal('starting_price', 10, 2)->default(0.00);
            $table->string('best_time_to_visit')->nullable();
            $table->string('climate')->nullable();
            
            // Boolean Toggles
            $table->boolean('is_domestic')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_popular')->default(false);
            
            $table->integer('sort_order')->default(0);
            $table->string('status')->default('active'); // active, inactive
            
            // SEO Meta fields
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('seo_keywords')->nullable();
            $table->string('og_image')->nullable();
            $table->text('schema_markup')->nullable();
            
            $table->unsignedBigInteger('created_by')->nullable();
            
            $table->softDeletes();
            $table->timestamps();
            
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('destinations');
    }
};
