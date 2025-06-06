<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemiluPollings extends Model
{
    use HasFactory;
    protected $table = 't_pemilu_pollings';
    protected $guarded = ['id'];
}
