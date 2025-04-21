<?php

namespace Modules\NewsAdmin\App\Models;

use Carbon\Traits\Timestamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\NewsAdmin\Database\factories\NewsAdminFactory;
use Modules\Regions\App\Models\Region;

class NewsAdmin extends Model
{
    use HasFactory;
    use Timestamp;

    /**
     * The attributes that are mass assignable.
     */

    protected $table = "t_news";
    protected $guarded = ['id'];

    protected $keyType = 'string';
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = $model->id ?? (string) \Illuminate\Support\Str::ulid();
        });
    }


    // belongsTo reference by region_id
    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    
    // protected static function newFactory(): NewsAdminFactory
    // {
    //     //return NewsAdminFactory::new();
    // }
}
