<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemiluCandidate extends Model
{
    use HasFactory;
    protected $table = 't_pemilu_candidates';
    protected $guarded = ['id'];
}
