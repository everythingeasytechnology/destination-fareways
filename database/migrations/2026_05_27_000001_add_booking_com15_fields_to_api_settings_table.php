<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('api_settings', function (Blueprint $table) {
            $table->string('rapidapi_host')->nullable()->after('base_url');
            $table->string('endpoint_location')->nullable()->after('mode');
            $table->string('endpoint_multi_stop')->nullable()->after('endpoint_search');
            $table->string('endpoint_details')->nullable()->after('endpoint_multi_stop');
            $table->string('endpoint_min_price')->nullable()->after('endpoint_details');
            $table->string('language_code', 10)->default('en-us')->after('currency');
        });
    }

    public function down(): void
    {
        Schema::table('api_settings', function (Blueprint $table) {
            $table->dropColumn([
                'rapidapi_host',
                'endpoint_location',
                'endpoint_multi_stop',
                'endpoint_details',
                'endpoint_min_price',
                'language_code',
            ]);
        });
    }
};
