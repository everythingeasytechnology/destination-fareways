<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'subtitle',
        'banner_image',
        'content',
        'seo_content',
        'show_breadcrumb',
        'status',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'og_title',
        'og_description',
        'og_image',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'from_airport_code',
        'to_airport_code',
        'schema_markup',
        'faq_schema',
        'breadcrumb_schema',
        'focus_keyword',
        'created_by',
    ];

    protected $casts = [
        'show_breadcrumb' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
