<?php

namespace Modules\Event\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\{Model , SoftDeletes };
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Community\App\Models\MemberEvent;
use Modules\Event\Database\factories\EventsFactory;

class Events extends Model
{
    use HasFactory  , SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     */
    // protected $fillable = [];
    protected $guarded = ["id"];

    protected $table = 't_event';

    protected $casts = [
        // "play_date_start" => "datetime",
        // "close_registration" => "date",
        "selected_fields" => "array",
    ];

    protected $primaryKey = 'id';

    // incrementing
    public $incrementing = true;

    // belongs to region 
    public function region_data()
    {
        return $this->belongsTo(\Modules\Regions\App\Models\Region::class, 'region', 'id');
    }
    public function attendees()
    {
        // return $this->belongsToMany(User::class,"t_member_event",  "t_event_id", "id");
        // return $this->belongsToMany(User::class, 't_member_event', 't_event_id', 'id');
        return $this->hasMany(MemberEvent::class, 't_event_id', 'id');
    }

    
    
}
