<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('subtitle')->nullable();
            $table->string('banner_image')->nullable();
            $table->longText('content')->nullable();
            $table->longText('seo_content')->nullable(); // custom SEO copy block
            $table->boolean('show_breadcrumb')->default(true);
            $table->string('status')->default('active'); // active, inactive
            
            // Full SEO Fields (9)
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('seo_keywords')->nullable();
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->string('twitter_title')->nullable();
            $table->text('twitter_description')->nullable();
            $table->string('twitter_image')->nullable();
            
            // Schema parameters
            $table->text('schema_markup')->nullable();
            $table->text('faq_schema')->nullable();
            $table->text('breadcrumb_schema')->nullable();
            $table->string('focus_keyword')->nullable();
            
            $table->unsignedBigInteger('created_by')->nullable();
            
            $table->softDeletes();
            $table->timestamps();
            
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
