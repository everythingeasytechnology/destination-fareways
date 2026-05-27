<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider',
        'base_url',
        'rapidapi_host',
        'api_key',
        'api_secret',
        'mode',
        'endpoint_location',
        'endpoint_search',
        'endpoint_multi_stop',
        'endpoint_details',
        'endpoint_min_price',
        'endpoint_booking',
        'endpoint_fare_rules',
        'endpoint_cancellation',
        'endpoint_refund',
        'endpoint_webhook',
        'api_status',
        'currency',
        'language_code',
        'markup_percent',
        'commission_percent',
        'last_error_log',
        'last_sync_at',
    ];

    protected $casts = [
        'api_key' => 'encrypted',
        'api_secret' => 'encrypted',
        'last_sync_at' => 'datetime',
        'markup_percent' => 'decimal:2',
        'commission_percent' => 'decimal:2',
    ];
}
