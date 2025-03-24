<?php

namespace Modules\MyGames\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\MyGames\Database\factories\InvitationPlayersFactory;

class InvitationPlayers extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = ['id'];
    protected $table = "t_invitation_players";
}
