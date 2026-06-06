<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FlightRoute extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'origin_city', 'origin_airport_code',
        'destination_city', 'destination_airport_code',
        'origin_country', 'destination_country',
        'short_desc', 'description', 'featured_image', 'banner_image',
        'starting_price', 'flight_duration', 'airlines', 'frequency',
        'is_featured', 'is_popular', 'is_domestic', 'sort_order', 'status',
        'seo_title', 'seo_description', 'seo_keywords', 'og_image',
        'schema_markup', 'faq_schema', 'canonical_url', 'created_by',
    ];

    protected $casts = [
        'starting_price' => 'decimal:2',
        'is_featured'    => 'boolean',
        'is_popular'     => 'boolean',
        'is_domestic'    => 'boolean',
        'sort_order'     => 'integer',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
