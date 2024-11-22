<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberShipModel extends Model
{
    use HasFactory;

    protected $table = 'memberships';

    protected $fillable = ['name', 'discount_percent'];
}
