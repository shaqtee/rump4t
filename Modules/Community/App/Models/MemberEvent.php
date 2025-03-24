<?php

namespace Modules\Community\App\Models;

use App\Models\User;
use App\Services\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Community\Database\factories\MemberEventFactory;

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
        return $this->BelongsTo(EventCommonity::class,  't_event_id');
    }

    public function scopeFilter($query, $request)
    {
        foreach ($request->all() as $key => $val) {
            if ($key === 'search' || $key === 'size' || $key === 'page') {
            } else {
                if ($request->has($key)) {
                    if ($val !== null) {
                        switch ($key) {
                            case 'player':
                                $query->whereHas('user', function($q) use($val) {
                                    $q->where('name', 'ilike', '%' . $val . '%');
                                });
                                break;
                            case 'event':
                                $query->whereHas('event', function($q) use($val){
                                    $q->where('title', 'ilike', '%' . $val . '%');
                                });
                            case 'approve':
                                $query->where('approve', 'ilike', '%' . $val . '%');
                            default:
                                $query->where($key, 'ilike', '%' . $val . '%');
                                break;
                        }
                    }
                }
            }
        }

        return $query;
    }

    public static function columnsWeb()
    {
        return Helper::columns([
            'Player' => 'string',
            'Event' => 'string',
            'Approve' => 'string',
        ]);
    }
}
