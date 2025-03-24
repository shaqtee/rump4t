<?php

namespace Modules\MyGames\App\Models;

use App\Models\User;
use Modules\MyGames\App\Models\Event;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\MyGames\Database\factories\MemberEventFactory;

class MemberEvent extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = "t_member_event";
    protected $guarded = ['id'];

    public function user(){
        return $this->BelongsTo(User::class,  't_user_id');
    }

    public function event(){
        return $this->BelongsTo(Event::class,  't_event_id');
    }
}
