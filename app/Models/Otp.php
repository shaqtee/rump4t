<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory;

    protected $table = 't_otp_log';
    const CREATE_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
    protected $guarded = ['id'];
}
