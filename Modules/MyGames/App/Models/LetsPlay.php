<?php

namespace Modules\MyGames\App\Models;

use App\Models\User;
use App\Services\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Modules\Masters\App\Models\MasterConfiguration;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Community\App\Models\TeeBoxCourse;
use Modules\Masters\App\Models\MasterRules;
use Modules\MyGames\Database\factories\LetsPlayFactory;

class LetsPlay extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = ['id'];
    protected $table = "t_lets_play";
    protected $appends = [
        'type_scor',
        'period',
        'flag_data'
    ];

    public function getFlagDataAttribute()
    {
        return 't_lets_play';
    }

    public function organized(){
        return $this->belongsTo(User::class, 't_user_id');
    }

    public function golfCourse(){
        return $this->belongsTo(MastersGolfCourse::class, 't_golf_course_id');
    }

    public function memberLetsPlay(){
        return $this->belongsToMany(User::class, 't_member_lets_play', 't_lets_play_id', 't_user_id');
    }

    public function teeBox(){
        return $this->belongsTo(TeeBoxCourse::class, 'm_tee_box_id');
    }

    public function roundType(){
        return $this->belongsTo(MasterConfiguration::class, 'm_round_type_id')->where('parameter', 'm_round_type');
    }

    // public function typeScore(){
    //     return $this->belongsTo(MasterConfiguration::class, 'm_type_scor_id')->where('parameter', 'm_type_scor');
    // }

    public function typeScore(){
        return $this->belongsTo(MasterRules::class, 'm_type_scor_id')->with('details');
    }

    public function getTypeScorAttribute(){
        // if ($this->m_type_scor_id == 1) {
        //     return "SYSTEM36";
        // } else if ($this->m_type_scor_id == 2) {
        //     return "STROKE PLAY";
        // } else if ($this->m_type_scor_id == 3) {
        //     return "STABLEFORD";
        // } else {
        //     return "-";
        // }

        return $this->typeScore ? $this->typeScore->name : '-';

    }

    public function getPeriodAttribute(){
        if ($this->periode == 1) {
            return "UPCOMMING";
        } else if ($this->periode == 2) {
            return "ONGOING";
        } else if ($this->periode == 3) {
            return "PASSED";
        } else if ($this->periode == 4) {
            return "CANCELED";
        } else {
            return "-";
        }
    }

    public function scopeActive(Builder $query)
    {
        $query->where('active', '1');
    }

    public function scopeFilter($query, $request){
        unset($request['type_games']);
        foreach ($request->all() as $key => $val) {
            if ($key === 'search' || $key === 'size' || $key === 'page') {
            } else {
                if ($request->has($key)) {
                    if ($val !== null) {
                        switch ($key) {
                            case 'scoring':
                                $val = explode(',', $val);
                                $query->whereIn('m_type_scor_id', $val);
                                break;
                            case 'games':
                                $query->where('t_user_id', $val);
                                break;
                            case 'periode':
                                $val = explode(',', $val);
                                $query->whereIn('periode', $val);
                                break;
                            case 'month':
                                $val = explode(',', $val);
                                $query->whereIn(DB::raw('EXTRACT(MONTH FROM "play_date")'), $val);
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

    public function scopeFilterWeb($query, $request){
        foreach ($request->all() as $key => $val) {
            if ($key === 'search' || $key === 'size' || $key === 'page') {
            } else {
                if ($request->has($key)) {
                    if ($val !== null) {
                        switch ($key) {
                            case 'title':
                                $query->where('title', 'ilike', '%' . $val . '%' );
                                break;
                            case 'golf_course':
                                $query->whereHas('golfCourse', function($q) use($val) {
                                    $q->where('name', 'ilike', '%' . $val . '%' );
                                });
                                break;
                            case 'play_date':
                                $query->whereDate('play_date', $val);
                                break;
                            case 'type_score':
                                $query->whereHas('typeScore', function($q) use($val){
                                    $q->where('value1', 'ilike', '%' . $val . '%');
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
        $configs = new MasterConfiguration;

        $typeScor = $configs->where('parameter', 'm_type_scor')->get();
        $period = $configs->where('parameter', 'm_period')->get();

        $month = [
            1 => "January",
            2 => "February",
            3 => "March",
            4 => "April",
            5 => "May",
            6 => "June",
            7 => "July",
            8 => "August",
            9 => "September",
            10 => "October",
            11 => "November",
            12 => "December"
        ];

        foreach ($typeScor as $ts) {
            $arr1[$ts->value1] = $ts->value2;
        }

        foreach ($period as $p) {
            $arr2[$p->value1] = $p->value2;
        }

        return Helper::columns([
            'Scoring' => $arr1,
            'Periode' => $arr2,
            'Month' => $month,
            'Games' => 'integer',
        ]);
    }

    public static function columnsWeb()
    {
        return Helper::columns([
            'Title' => 'string',
            'Golf Course' => 'string',
            'Play Date' => 'date',
            'Type Score' => 'string',
        ]);
    }
}
