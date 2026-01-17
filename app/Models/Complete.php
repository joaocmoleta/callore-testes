<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complete extends Model
{
    use HasFactory;

    protected $fillable = [
        'user',
        'tax_id',
        'postal_code',
        'street',
        'number',
        'complement',
        'locality',
        'city', 
        'region', 
        'region_code', 
        'country'
    ];
}
