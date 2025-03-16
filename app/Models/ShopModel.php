<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ShopModel extends Authenticatable
{
    use HasFactory;
    protected $table = 'shops';
    protected $fillable = [
        'phone',
        'name',
        'password',
        'commission_percentage',
        'content',
        'src',
        'display',
        'slug'
    ];

    public function categories()
    {
        return $this->belongsToMany(CategoryModel::class, 'shop_category', 'shop_id', 'category_id');
    }

    public function products()
    {
        return $this->hasMany(ShopProductModel::class, 'shop_id', 'id')
            ->where('display', 1)
            ->where(function($query) {
                $query->whereNull('start_date')
                    ->orWhere('start_date', '<=', Carbon::now());
            })
            ->where(function($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', Carbon::now());
            })
            ->where('quantity', '>', 0)
            ->select('id', 'shop_id', 'name', 'src', 'price', 'slug')
            ->limit(3);
    }
}
