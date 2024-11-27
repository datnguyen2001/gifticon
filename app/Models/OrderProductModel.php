<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProductModel extends Model
{
    use HasFactory;

    protected $table = 'order_products';
    protected $fillable = [
        'order_id',
        'product_id',
        'shop_id',
        'message',
        'quantity',
        'unit_price',
        'buy_for',
        'receiver_phone',
        'barcode'
    ];

    public function product()
    {
        return $this->belongsTo(ShopProductModel::class, 'product_id');
    }

}
