<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'order_date',
        'order_number',
        'party_name',
        'gst_no',
        'party_city',
        'party_phone',
        'series',
        'code_no',
        'size',
        'auto_rent',
        'vehicle_rent',
        'transport',
        'paid_by',
        'total_amount',
        'delivery_from',
        'package_no',
        'purchase_no',
        'sell_bill_no',
        'bank_name',
        'date',
        'cash_received_by',
        'confirmed',
    ];
}
