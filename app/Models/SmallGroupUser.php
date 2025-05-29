<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmallGroupUser extends Model
{
    use HasFactory;
    protected $table = 't_small_groups_user';
    protected $guarded = ['id'];
}
