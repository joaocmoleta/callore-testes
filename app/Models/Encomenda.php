<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encomenda extends Model
{
    use HasFactory;
    protected $fillable = ['order', 'pedido', 'cliente_codigo', 'tipo_servico', 'data', 'hora', 'volumes'];
}
