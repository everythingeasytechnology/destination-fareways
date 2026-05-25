<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'subtitle',
        'short_desc',
        'description',
        'image',
        'banner_image',
        'from_city',
        'to_city',
        'airline',
        'original_price',
        'offer_price',
        'discount_label',
        'promo_code',
        'valid_from',
        'valid_until',
        'is_featured',
        'status',
        'sort_order',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'og_image',
        'created_by',
    ];

    protected $casts = [
        'valid_from' => 'date',
        'valid_until' => 'date',
        'is_featured' => 'boolean',
        'original_price' => 'decimal:2',
        'offer_price' => 'decimal:2',
        'sort_order' => 'integer',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
