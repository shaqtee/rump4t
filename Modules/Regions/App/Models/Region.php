<?php

namespace Modules\Regions\App\Models;

use Carbon\Traits\Timestamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Regions\Database\factories\RegionFactory;

class Region extends Model
{
    use HasFactory , Timestamp , SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = 't_regions';

    // ulid generate
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) \Illuminate\Support\Str::ulid();
        });
    }
    
    protected static function newFactory(): RegionFactory
    {
        //return RegionFactory::new();
    }
}
