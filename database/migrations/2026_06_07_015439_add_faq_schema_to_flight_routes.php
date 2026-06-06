<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('flight_routes', function (Blueprint $table) {
            $table->longText('faq_schema')->nullable()->after('schema_markup');
        });
    }

    public function down(): void
    {
        Schema::table('flight_routes', function (Blueprint $table) {
            $table->dropColumn('faq_schema');
        });
    }
};
