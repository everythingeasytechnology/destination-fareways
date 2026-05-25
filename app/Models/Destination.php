<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Destination extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'country',
        'state',
        'airport_code',
        'short_desc',
        'description',
        'featured_image',
        'banner_image',
        'gallery',
        'starting_price',
        'best_time_to_visit',
        'climate',
        'is_domestic',
        'is_featured',
        'is_popular',
        'sort_order',
        'status',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'og_image',
        'schema_markup',
        'created_by',
    ];

    protected $casts = [
        'gallery' => 'array',
        'starting_price' => 'decimal:2',
        'is_domestic' => 'boolean',
        'is_featured' => 'boolean',
        'is_popular' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
