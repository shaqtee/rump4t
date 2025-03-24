<?php

namespace Modules\Community\App\Models;

use App\Services\Helpers\Helper;
use App\Traits\Blameable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Community\Database\factories\PhotoCommonityFactory;

class PhotoCommonity extends Model
{
    use Blameable;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = "t_photo";
    const CREATE_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $guarded = ['id'];

    public function photoCommonity(){
        return $this->belongsTo(AlbumCommonity::class, 't_album_id');
    }
    
    public function photoEvent(){
        return $this->belongsTo(AlbumCommonity::class, 't_album_id');
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
                            case 'album':
                                $query->where('t_album_id', $val);
                                break;
                            case 'albums':
                                $query->whereHas('photoCommonity', function($q) use($val){
                                    $q->where('name', 'ilike', '%' . $val . '%');
                                });
                                break;    
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
            'Album' => 'integer',
        ]);
    }

    public static function columnsWeb()
    {
        return Helper::columns([
            'Name' => 'string',
            'Albums' => 'string',
        ]);
    }
}
