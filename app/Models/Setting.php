<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_name',
        'tagline',
        'logo',
        'favicon',
        'primary_email',
        'secondary_email',
        'primary_phone',
        'secondary_phone',
        'whatsapp',
        'address',
        'city',
        'state',
        'country',
        'zip',
        'social_facebook',
        'social_twitter',
        'social_instagram',
        'social_linkedin',
        'social_youtube',
        'analytics_google_id',
        'analytics_facebook_pixel',
        'analytics_custom_code',
        'header_scripts',
        'footer_scripts',
        'copyright',
        'maintenance_mode',
    ];
}
