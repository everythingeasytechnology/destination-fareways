<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flight_routes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('origin_city');
            $table->string('origin_airport_code', 10)->nullable();
            $table->string('destination_city');
            $table->string('destination_airport_code', 10)->nullable();
            $table->string('origin_country', 100)->default('USA');
            $table->string('destination_country', 100)->default('USA');
            $table->text('short_desc')->nullable();
            $table->longText('description')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('banner_image')->nullable();
            $table->decimal('starting_price', 10, 2)->default(0.00);
            $table->string('flight_duration')->nullable();
            $table->string('airlines')->nullable();
            $table->string('frequency')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_domestic')->default(true);
            $table->integer('sort_order')->default(0);
            $table->string('status')->default('active');
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('seo_keywords')->nullable();
            $table->string('og_image')->nullable();
            $table->text('schema_markup')->nullable();
            $table->string('canonical_url')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flight_routes');
    }
};
