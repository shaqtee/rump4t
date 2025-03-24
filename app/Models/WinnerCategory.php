<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Community\App\Models\EventCommonity;

class WinnerCategory extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 't_winner_category';

    public function event(){
        return $this->belongsTo(EventCommonity::class, 't_event_id');
    }
}
