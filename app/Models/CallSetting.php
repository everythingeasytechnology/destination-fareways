<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'whatsapp',
        'button_text',
        'button_color',
        'text_color',
        'toggle_header',
        'toggle_footer',
        'toggle_mobile_floating',
        'toggle_desktop',
        'toggle_whatsapp',
        'floating_position',
        'cta_text',
        'cta_phone',
        'cta_subtext',
        'status',
    ];

    protected $casts = [
        'toggle_header' => 'boolean',
        'toggle_footer' => 'boolean',
        'toggle_mobile_floating' => 'boolean',
        'toggle_desktop' => 'boolean',
        'toggle_whatsapp' => 'boolean',
        'status' => 'boolean',
    ];
}
