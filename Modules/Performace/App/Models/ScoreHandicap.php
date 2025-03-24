<?php

namespace Modules\Performace\App\Models;

use App\Models\User;
use App\Services\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Modules\Community\App\Models\EventCommonity;
use Modules\ScoreHandicap\App\Models\LetsPlaySH;
use Modules\MyGames\App\Models\MastersGolfCourse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Performace\Database\factories\ScoreHandicapFactory;

class ScoreHandicap extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = ['id'];
    protected $table = 't_score_handicap';

    public function user(){
        return $this->belongsTo(User::class, 't_user_id');
    }

    public function event(){
        return $this->belongsTo(EventCommonity::class, 't_event_id');
    }

    public function letsPlay(){
        return $this->belongsTo(LetsPlaySH::class, 't_lets_play_id');
    }

    public function golfCourse(){
        return $this->belongsTo(MastersGolfCourse::class, 't_course_id');
    }

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
