<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SafraPedidos extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'order',
        'charge_id',
        'aditumNumber',
        'qrCode',
        'qrCodeImage',
        'transactionId',
        'paymentType',
        'amount',
        'acquirer',
        'creationDateTime',
        'nsu'
    ];
}
