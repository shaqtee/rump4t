<?php

namespace Modules\SocialMedia\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\SocialMedia\Database\factories\UserBlockFactory;

class UserBlock extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 't_user_block';
}
