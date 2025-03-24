<?php

namespace Modules\MyGames\App\Models;

use App\Models\User;
use App\Services\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Modules\MyGames\App\Models\Community;
use Modules\Masters\App\Models\MasterCity;
use Modules\Community\App\Models\GolfCourse;
use Modules\Community\App\Models\TeeBoxCourse;
use Modules\Masters\App\Models\MasterConfiguration;
use Modules\MyGames\Database\factories\EventFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = "t_event";
    const CREATE_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $guarded = ['id'];
    protected $appends = [
        'periode',
        'type_scor',
        'flag_data'
    ];

    public function getFlagDataAttribute()
    {
        return 't_event';
    }

    public function eventCommonity(){
        return $this->belongsTo(Community   ::class, 't_community_id');
    }

    public function membersEvent(){
        return $this->belongsToMany(User::class, 't_member_event', 't_event_id', 't_user_id');
    }

    public function teeMan(){
        return $this->belongsTo(TeeBoxCourse::class, 't_tee_man_id');
    }

    public function teeLadies(){
        return $this->belongsTo(TeeBoxCourse::class, 't_tee_ladies_id');
    }

    public function golfCourseEvent(){
        return $this->belongsTo(GolfCourse::class, 'm_golf_course_id');
    }

    public function roundType(){
        return $this->BelongsTo(MasterConfiguration::class, 'm_round_type_id')->where('parameter', 'm_round_type');
    }

    public function scopeActive($query)
    {
        $query->where('active', '1');
    }

    public function getPeriodeAttribute(){
        if ($this->period == 1) {
            return "UPCOMMING";
        } else if ($this->period == 2) {
            return "ONGOING";
        } else if ($this->period == 3) {
            return "PASSED";
        } else {
            return "-";
        }
    }

    public function getTypeScorAttribute(){
        // if ($this->type_scoring == 1) {
        //     return "SYSTEM36";
        // } else if ($this->type_scoring == 2) {
        //     return "STROKE PLAY";
        // } else if ($this->type_scoring == 3) {
        //     return "STABLEFORD";
        // } else {
        //     return "-";
        // }

        return $this->rule ? $this->rule->name : '-';

    }

    public function scopeFilter($query, $request)
    {
        // if ($request->has('search')) {
        //     $key = $request->search;
        // }
        unset($request['type_games']);
        foreach ($request->all() as $key => $val) {
            if ($key === 'search' || $key === 'size' || $key === 'page') {
            } else {
                if ($request->has($key)) {
                    if ($val !== null) {
                        switch ($key) {
                            case 'id':
                                $query->where('id', $val);
                                break;
                            case 'title':
                                $query->where('title', 'ilike', '%' . $val . '%');
                                break;
                            case 'description':
                                $query->where('description', 'ilike', '%' . $val . '%');
                                break;
                            case 'community':
                                $query->where('t_community_id', $val);
                                break;
                            case 'location':
                                $query->where('location', 'ilike', '%' . $val . '%');
                                break;
                            case 'type_scoring':
                                $val = explode(',', $val);
                                $query->whereIn('type_scoring', $val);
                                break;
                            case 'play_date':
                                $val = explode(',', $val);
                                $query->whereIn(DB::raw('EXTRACT(MONTH FROM "play_date_start")'), $val);
                                break;
                            case 'period':
                                $val = explode(',', $val);
                                $query->whereIn('period', $val);
                                break;
                            case 'region':
                                $val = explode(',', $val);
                                $query->whereIn('m_city_id', $val);
                                break;
                            case 'payment':
                                $val = explode(',', $val);
                                $query->whereHas('membersEvent',  function ($q) use ($val) {
                                    $q->whereIn('approve', $val);
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
        $masterCity = MasterCity::where('is_staging', '1')->get();

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

        $payment = [
            'PAID',
            'WAITING_FOR_PAYMENT',
            'CANCEL',
        ];

        foreach ($typeScor as $ts) {
            $arr1[$ts->value1] = $ts->value2;
        }

        foreach ($period as $p) {
            $arr2[$p->value1] = $p->value2;
        }

        foreach ($masterCity as $city) {
            $arr3[$city->name] = $city->id;
        }

        return Helper::columns([
            'Id' => 'string',
            'Title' => 'string',
            'Description' => 'string',
            'Community' => 'integer',
            'Location' => 'string',
            'Type Scoring' => $arr1,
            'Play Date' => $month,
            'Period' => $arr2,
            'Region' => $arr3,
            'Payment' => $payment,
        ]);
    }
}
