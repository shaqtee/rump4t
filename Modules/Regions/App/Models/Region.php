<?php

namespace Modules\Regions\App\Models;

use Carbon\Traits\Timestamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Regions\Database\factories\RegionFactory;

class Region extends Model
{


    /**
     * The attributes that are mass assignable.
     */
    // protected $fillable = [];
    protected $keyType = 'integer';
    public $incrementing = true;

    protected $table = 'm_references';


}
