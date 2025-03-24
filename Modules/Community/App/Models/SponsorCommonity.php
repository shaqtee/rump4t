<?php

namespace Modules\Community\App\Models;

use App\Traits\Blameable;
use App\Services\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Modules\Community\App\Models\Community;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Community\Database\factories\SponsorCommonityFactory;

class SponsorCommonity extends Model
{
    use Blameable;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = "t_sponsor";
    const CREATE_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $guarded = ['id'];

    public function sponsorCommonity(){
        return $this->belongsTo(Community::class, 't_community_id');
    }

    public function sponsorEvent(){
        return $this->belongsTo(EventCommonity::class, 't_event_id');
    }

    public function socialMedia(){
        return $this->hasOne(SocialMedia::class,'table_id')->where('table_name', 't_sponsor');
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
                            case 'sponsor_name':
                                $query->where('name', 'ilike', '%' . $val . '%');
                                break;
                            case 'community':
                                $query->where('t_community_id', $val);
                                break;
                            case 'name':
                                $query->where('name', 'ilike', '%' . $val . '%');
                                break;
                            case 'communities':
                                $query->whereHas('sponsorCommonity', function($q) use($val) {
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
            'Sponsor Name' => 'string',
            'Community' => 'integer',
        ]);
    }

    public static function columnsWeb()
    {
        return Helper::columns([
            'Name' => 'string',
            'Communities' => 'string',
        ]);
    }
}
