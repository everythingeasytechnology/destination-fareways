<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('from_city');
            $table->string('to_city');
            $table->date('travel_date');
            $table->date('return_date')->nullable();
            $table->integer('passengers')->default(1);
            
            // Enums
            $table->string('cabin_class')->default('economy'); // economy, premium_economy, business, first
            $table->string('trip_type')->default('round_trip'); // one_way, round_trip, multi_city
            
            $table->text('message')->nullable();
            $table->string('source_page')->nullable();
            $table->string('ip')->nullable();
            $table->string('status')->default('new'); // new, contacted, converted, closed
            $table->text('admin_notes')->nullable();
            
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
