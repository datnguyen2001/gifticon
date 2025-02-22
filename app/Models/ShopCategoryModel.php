<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopCategoryModel extends Model
{
    use HasFactory;
    protected $table='shop_category';
    protected $fillable=[
        'shop_id',
        'category_id'
    ];
}
