<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = ['order', 'numero', 'serie', 'data_emissao', 'natureza', 'total', 'produto', 'chave', 'file', 'volumes'];
}
