<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'pay_id',
        'amount',
        'type',
        'installments',
        'cd_brand',
        'cd_first_digits',
        'cd_last_digits',
        'cd_exp_month',
        'cd_exp_year',
        'cd_holder_name',
        'tax_id',
        'postal_code',
        'street',
        'number',
        'complement',
        'locality',
        'city', 
        'region',
        'region_code', 
        'country',
        'boleto_id',
        'links',
        'barcode',
        'due_date',
    ];
}
