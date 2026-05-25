<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_settings', function (Blueprint $table) {
            $table->id();
            
            $table->string('provider');
            $table->string('base_url');
            $table->text('api_key')->nullable();
            $table->text('api_secret')->nullable();
            $table->string('mode')->default('sandbox'); // sandbox or live
            
            // Endpoints
            $table->string('endpoint_search')->nullable();
            $table->string('endpoint_booking')->nullable();
            $table->string('endpoint_fare_rules')->nullable();
            $table->string('endpoint_cancellation')->nullable();
            $table->string('endpoint_refund')->nullable();
            $table->string('endpoint_webhook')->nullable();
            
            // Financials and Logs
            $table->string('api_status')->default('inactive'); // active, inactive, error
            $table->string('currency')->default('USD');
            $table->decimal('markup_percent', 5, 2)->default(0.00);
            $table->decimal('commission_percent', 5, 2)->default(0.00);
            
            $table->text('last_error_log')->nullable();
            $table->timestamp('last_sync_at')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_settings');
    }
};
