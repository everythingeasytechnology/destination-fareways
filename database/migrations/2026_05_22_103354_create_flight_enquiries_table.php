<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flight_enquiries', function (Blueprint $table) {
            $table->id();
            
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('from_airport');
            $table->string('to_airport');
            $table->date('departure_date');
            $table->date('return_date')->nullable();
            
            $table->integer('adults')->default(1);
            $table->integer('children')->default(0);
            $table->integer('infants')->default(0);
            
            $table->string('cabin_class')->default('economy'); // economy, premium_economy, business, first
            $table->string('trip_type')->default('round_trip'); // one_way, round_trip, multi_city
            
            $table->string('preferred_airline')->nullable();
            $table->string('budget')->nullable();
            $table->text('special_requests')->nullable();
            $table->string('ip')->nullable();
            $table->string('status')->default('new'); // new, reviewed, quoted, booked, cancelled
            $table->text('admin_notes')->nullable();
            
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flight_enquiries');
    }
};
