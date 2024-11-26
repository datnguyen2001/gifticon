<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderReceiverModel extends Model
{
    use HasFactory;
    protected $table = 'order_receivers';
    protected $fillable = [
        'order_id',
        'order_product_id',
        'phone'
    ];
}
