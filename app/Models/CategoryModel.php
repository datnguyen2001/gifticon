<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryModel extends Model
{
    use HasFactory;

    protected $table="category";
    protected $fillable=[
        'src',
        'name',
        'slug',
        'display'
    ];

    public function shops()
    {
        return $this->belongsToMany(ShopModel::class, 'shop_category', 'category_id', 'shop_id');
    }
}
