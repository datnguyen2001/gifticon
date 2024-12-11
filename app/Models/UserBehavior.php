<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBehavior extends Model
{
    use HasFactory;

    protected $table = 'user_behaviors';

    protected $fillable = ['date', 'path', 'page_view'];

    protected $casts = [
        'date' => 'datetime',
    ];
}
