<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'subtitle',
        'excerpt',
        'content',
        'featured_image',
        'banner_image',
        'author_name',
        'author_image',
        'category',
        'tags',
        'read_time',
        'views',
        'is_featured',
        'status',
        'published_at',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'og_image',
        'canonical_url',
        'schema_markup',
        'created_by',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
        'views' => 'integer',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
