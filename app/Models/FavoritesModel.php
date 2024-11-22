<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoritesModel extends Model
{
    use HasFactory;
    protected $table="favorites";
    protected $fillable=[
        'user_id',
        'product_id',
    ];

    public function product()
    {
        return $this->belongsTo(ShopProductModel::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
