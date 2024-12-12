<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReviewModel extends Model
{
    use HasFactory;

    protected $table = 'product_reviews';

    protected $fillable = [
        'user_id',
        'product_id',
        'content',
        'star'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
