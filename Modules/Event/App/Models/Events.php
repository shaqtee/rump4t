<?php

namespace Modules\Event\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Event\Database\factories\EventsFactory;

class Events extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    protected $table = 't_event';

    protected $primaryKey = 'id';

    // incrementing
    public $incrementing = true;
    
}
