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
    protected $fillable = [];

    protected $table = 't_event';

    protected $primaryKey = 'id';

    // incrementing
    public $incrementing = true;
    
}
