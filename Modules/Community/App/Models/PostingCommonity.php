<?php

namespace Modules\Community\App\Models;

use App\Traits\Blameable;
use App\Services\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Community\Database\factories\PostingFactory;

class PostingCommonity extends Model
{
    use Blameable;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = "t_posting";
    const CREATE_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $guarded = ['id'];
    
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->setTimezone(new \DateTimeZone('Asia/Jakarta'))->format($this->created_at);
    }

    public function postingCommonity(){
        return $this->belongsTo(Community::class, 't_community_id');
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
                            case 'title':
                                $query->where('title', 'ilike',  "%{$val}%");
                                break;
                            case 'created_at':
                                $query->whereDate('created_at', $val);
                                break;
                            case 'community':
                                $query->where('t_community_id', $val);
                                break;
                            case 'communities':
                                $query->whereHas('postingCommonity', function($q) use($val){
                                    $q->where('title', 'ilike', '%' . $val . '%');
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
            'Title' => 'string',
            'Created At' => 'date',
            'Community' => 'integer',
        ]);
    }

    public static function columnsWeb()
    {
        return Helper::columns([
            'Title' => 'string',
            'Communities' => 'string',
        ]);
    }
}
