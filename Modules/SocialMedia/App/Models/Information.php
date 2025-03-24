<?php

namespace Modules\SocialMedia\App\Models;

use App\Services\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Community\App\Models\EventCommonity;

class Information extends Model
{
    use HasFactory;

    protected $table = "t_informations";

    protected $guarded = ['id'];
    const CREATE_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function events() {
        return $this->belongsTo(EventCommonity::class, 't_event_id', 'id');
    }

    public function scopeFilter($query, $request)
    {
        foreach ($request->all() as $key => $val) {
            if ($key === 'search' || $key === 'size' || $key === 'page') {
            } else {
                if ($request->has($key)) {
                    if ($val !== null) {
                        switch ($key) {
                            case 'title':
                                $query->where('title', 'ilike', '%' . $val . '%');
                                break;
                            case 'description':
                                $query->where('description', 'ilike', '%' . $val . '%');
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

    public function columns()
    {
        return Helper::columns([
            'Title' => 'string',
        ]);
    }

    public function columnsWeb()
    {
        return Helper::columns([
            'Title' => 'string',
        ]);
    }
}
