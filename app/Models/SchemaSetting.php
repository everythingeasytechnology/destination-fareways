<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchemaSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_identifier',
        'schema_type',
        'schema_json',
        'status',
    ];
}
