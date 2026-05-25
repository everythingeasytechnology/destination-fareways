<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('call_settings', function (Blueprint $table) {
            $table->id();
            
            $table->string('phone');
            $table->string('whatsapp')->nullable();
            $table->string('button_text')->default('Call Now');
            $table->string('button_color')->default('#F59E0B');
            $table->string('text_color')->default('#FFFFFF');
            
            // Toggles
            $table->boolean('toggle_header')->default(true);
            $table->boolean('toggle_footer')->default(true);
            $table->boolean('toggle_mobile_floating')->default(true);
            $table->boolean('toggle_desktop')->default(true);
            $table->boolean('toggle_whatsapp')->default(true);
            
            // Positioning & Call-To-Action (CTA) details
            $table->string('floating_position')->default('bottom-right'); // e.g. bottom-right, bottom-left
            $table->string('cta_text')->nullable();
            $table->string('cta_phone')->nullable();
            $table->string('cta_subtext')->nullable();
            
            $table->boolean('status')->default(true);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('call_settings');
    }
};
