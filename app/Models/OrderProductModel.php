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
        'message',
        'quantity',
        'buy_for'
    ];

    public function orderReceivers()
    {
        return $this->hasMany(OrderReceiverModel::class, 'order_product_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(ShopProductModel::class, 'product_id');
    }

}
