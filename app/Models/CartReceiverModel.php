<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartReceiverModel extends Model
{
    use HasFactory;

    protected $table = 'cart_receivers';
    protected $fillable = [
        'cart_id',
        'phone',
        'quantity'
    ];
}
