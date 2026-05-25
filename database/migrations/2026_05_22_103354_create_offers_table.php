<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('subtitle')->nullable();
            $table->text('short_desc')->nullable();
            $table->longText('description')->nullable();
            $table->string('image')->nullable();
            $table->string('banner_image')->nullable();
            
            // Route details
            $table->string('from_city')->nullable();
            $table->string('to_city')->nullable();
            $table->string('airline')->nullable();
            
            // Pricing details
            $table->decimal('original_price', 10, 2);
            $table->decimal('offer_price', 10, 2);
            $table->string('discount_label')->nullable(); // e.g. 20% OFF
            $table->string('promo_code')->nullable();
            
            // Dates and states
            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('status')->default('active'); // active, inactive
            $table->integer('sort_order')->default(0);
            
            // SEO Meta fields
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('seo_keywords')->nullable();
            $table->string('og_image')->nullable();
            
            $table->unsignedBigInteger('created_by')->nullable();
            
            $table->softDeletes();
            $table->timestamps();
            
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
