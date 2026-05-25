<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schema_settings', function (Blueprint $table) {
            $table->id();
            
            $table->string('page_identifier');
            $table->string('schema_type'); // e.g. Organization, FAQPage, BreadcrumbList, FlightReservation
            $table->longText('schema_json'); // JSON-LD payload
            $table->string('status')->default('active'); // active, inactive
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schema_settings');
    }
};
