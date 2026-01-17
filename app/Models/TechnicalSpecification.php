<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechnicalSpecification extends Model
{
    use HasFactory;

    protected $fillable = [
        'product',
        'type',
        'color',
        'voltage',
        'wattage',
        'cable',
        'material',
        'paint',
        'indicate',
        'suporte_paredes',
        'suporte_chao',
        'sizes',
        'pack_sizes',
        'line',
        'manufacturer',
        'guarantee',
    ];
}
