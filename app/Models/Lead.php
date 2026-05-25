<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'from_city',
        'to_city',
        'travel_date',
        'return_date',
        'passengers',
        'cabin_class',
        'trip_type',
        'message',
        'source_page',
        'ip',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'travel_date' => 'date',
        'return_date' => 'date',
        'passengers' => 'integer',
    ];
}
