<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('from_airport_code')->nullable()->after('twitter_image');
            $table->string('to_airport_code')->nullable()->after('from_airport_code');
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['from_airport_code', 'to_airport_code']);
        });
    }
};
