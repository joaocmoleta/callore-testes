<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagarmePedidos extends Model
{
    use HasFactory;

    protected $fillable = [
        'order',
        'id_order',
        'code',
        'closed',
        'pg_created_at',
        'pg_updated_at',
        'pg_closed_at',
        'charge_id',
        'charge_code',
        'gateway_id',
        'paid_amount',
        'qr_code',
        'qr_code_url',
        'expires_at',
        'transaction_id',
        'antifraud_status',
        'antifraud_score',
    ];
}
