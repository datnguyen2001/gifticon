<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartModel extends Model
{
    use HasFactory;

    protected $table = 'carts';
    protected $fillable = [
        'user_id',
        'product_id',
        'total_price',
        'quantity',
        'buy_for',
        'message',
        'is_selected',
        'type'
    ];

    public function product()
    {
        return $this->belongsTo(ShopProductModel::class, 'product_id', 'id');
    }

    public function receivers()
    {
        return $this->hasMany(CartReceiverModel::class, 'cart_id', 'id');
    }

}
