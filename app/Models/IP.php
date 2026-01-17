<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IP extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip',
        'country',
        'country_code',
        'region',
        'region_code',
        'city',
        'district',
        'cep',
        'latitude',
        'longitude',
    ];
}
