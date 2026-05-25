<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            
            // Site Info
            $table->string('site_name');
            $table->string('tagline')->nullable();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            
            // Contact details
            $table->string('primary_email');
            $table->string('secondary_email')->nullable();
            $table->string('primary_phone');
            $table->string('secondary_phone')->nullable();
            $table->string('whatsapp')->nullable();
            
            // Address
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('country')->default('USA');
            $table->string('zip');
            
            // Social Media Links (5)
            $table->string('social_facebook')->nullable();
            $table->string('social_twitter')->nullable();
            $table->string('social_instagram')->nullable();
            $table->string('social_linkedin')->nullable();
            $table->string('social_youtube')->nullable();
            
            // Analytics Scripts (3)
            $table->string('analytics_google_id')->nullable();
            $table->string('analytics_facebook_pixel')->nullable();
            $table->text('analytics_custom_code')->nullable();
            
            // Custom Scripts
            $table->longText('header_scripts')->nullable();
            $table->longText('footer_scripts')->nullable();
            
            // Extra
            $table->string('copyright')->nullable();
            $table->string('maintenance_mode')->default('inactive'); // active or inactive
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
