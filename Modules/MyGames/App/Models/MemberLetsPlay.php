<?php

namespace Modules\MyGames\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\MyGames\Database\factories\MemberLetsPlayFactory;

class MemberLetsPlay extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = ['id'];
    protected $table = "t_member_lets_play";

    public function organized(){
        return $this->belongsTo(User::class, 't_user_id');
    }

    public function letsPlay(){
        return $this->belongsTo(LetsPlay::class, 't_lets_play_id');
    }
}
