<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterModel extends Model
{
    use HasFactory;
    protected $table='footers';
    protected $fillable=[
        'name',
        'slug',
        'content',
        'type',
    ];
}
