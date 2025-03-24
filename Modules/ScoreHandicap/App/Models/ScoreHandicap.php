<?php

namespace Modules\ScoreHandicap\App\Models;

use App\Services\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScoreHandicap extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = ['id'];
    protected $table = 't_score_handicap';

    public function scopeFilter($query, $request)
    {
        foreach ($request->all() as $key => $val) {
            if ($key === 'search' || $key === 'size' || $key === 'page') {
            } else {
                if ($request->has($key)) {
                    if ($val !== null) {
                        switch ($key) {
                            case 'course':
                                $query->where('course',  '%' . $val . '%');
                                break;
                            case 'date':
                                $query->whereDate('date', $val);
                                break;
                            case 'tee':
                                $query->where('tee', 'ilike', '%' . $val . '%');
                                break;
                            case 'round_type':
                                $query->where('round_type', 'ilike', '%' . $val . '%');
                                break;
                            case 'gross_score':
                                $query->where('gross_score', 'ilike', '%' . $val . '%');
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
            'Course' => 'string',
            'Date' => 'date',
            'Tee' => 'string',
            'Round Type' => 'string',
            'Gross Score' => 'string',
        ]);
    }
}
