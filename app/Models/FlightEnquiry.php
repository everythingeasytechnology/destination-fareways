<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FlightEnquiry extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'from_airport',
        'to_airport',
        'departure_date',
        'return_date',
        'adults',
        'children',
        'infants',
        'cabin_class',
        'trip_type',
        'preferred_airline',
        'budget',
        'special_requests',
        'ip',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'departure_date' => 'date',
        'return_date' => 'date',
        'adults' => 'integer',
        'children' => 'integer',
        'infants' => 'integer',
    ];
}
