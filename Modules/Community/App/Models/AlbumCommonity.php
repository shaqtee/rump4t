<?php

namespace Modules\Community\App\Models;

use App\Traits\Blameable;
use App\Services\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Modules\Community\App\Models\Community;
use Modules\Community\App\Models\EventCommonity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Community\Database\factories\AlbumCommonityFactory;

class AlbumCommonity extends Model
{
    use Blameable;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = "t_album";
    const CREATE_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $guarded = ['id'];

    public function photoCommonity(){
        return $this->hasMany(PhotoCommonity::class,'t_album_id');
    }

    public function photoAlbum() {
        return $this->hasMany(PhotoCommonity::class,'t_album_id');
    }

    public function albumCommonity(){
        return $this->belongsTo(Community::class,'t_community_id');
    }

    public function albumEvent(){
        return $this->belongsTo(EventCommonity::class,'t_event_id');
    }

    public function scopeActive(Builder $query)
    {
        $query->where('active', '1');
    }
    
    public function scopeFilter($query, $request)
    {
        // if ($request->has('search')) {
        //     $key = $request->search;
        //     $query->where('parameter', 'ilike', '%' . $key . '%')->orWhere('value1', 'ilike', '%' . $key . '%')->orWhere('value2', 'ilike', '%' . $key . '%')->orWhere('value3', 'ilike', '%' . $key . '%')->orWhere('remark', 'ilike', '%' . $key . '%');
        // }

        foreach ($request->all() as $key => $val) {
            if ($key === 'search' || $key === 'size' || $key === 'page') {
            } else {
                if ($request->has($key)) {
                    if ($val !== null) {
                        switch ($key) {
                            case 'name':
                                $query->where('name', 'ilike', '%' . $val . '%');
                                break;
                            case 'description':
                                $query->where('description', 'ilike', '%' . $val . '%');
                                break;
                            case 'community':
                                $query->where('t_community_id', $val);
                                break;
                            case 'Communities':
                                $query->whereHas('albumCommonity', function($q) use($val){
                                    $q->where('title', 'ilike', '%' . $val . '%');
                                });
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

    public static function columns()
    {
        return Helper::columns([
            'Name' => 'string',
            'Description' => 'string',
            'Community' => 'integer',
        ]);
    }

    public static function columnsWeb()
    {
        return Helper::columns([
            'Name' => 'string',
            'Communities' => 'integer',
        ]);
    }

}
