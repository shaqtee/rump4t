<?php

namespace Modules\Event\App\Models;

use Illuminate\Database\Eloquent\{Model , SoftDeletes };
use Illuminate\Database\Eloquent\Factories\HasFactory;
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

    protected $cast = [
        "play_date_start" => "datetime",
        "close_registration" => "date",
    ];

    protected $primaryKey = 'id';

    // incrementing
    public $incrementing = true;

    // belongs to region 
    public function region()
    {
        return $this->belongsTo(\Modules\Regions\App\Models\Region::class, 'region_id', 'id');
    }
    
}
