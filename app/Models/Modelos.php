<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelos extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'a_partir_de', 'imagens', 'abstract', 'description'
    ];
}
