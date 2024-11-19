<?php

namespace App\Models;

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
        'content',
        'src',
        'display'
    ];
}
