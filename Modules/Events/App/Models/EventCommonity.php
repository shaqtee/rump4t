<?php

namespace Modules\Events\App\Models;

use Carbon\Carbon;
use App\Models\User;
use App\Traits\Blameable;
use App\Services\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Modules\Masters\App\Models\MasterCity;
use Modules\Community\App\Models\Community;
use Modules\Masters\App\Models\MasterConfiguration;
use Modules\Community\App\Models\WinnerCategoryEvent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Community\Database\factories\EventCommonityFactory;
use Modules\Masters\App\Models\MasterRules;

class EventCommonity extends Model
{
    use Blameable;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = "t_event";
    const CREATE_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $guarded = ['id'];
    protected $appends = [
        'periode',
        'type_scor'
    ];
    // protected $hidden = [
    //     'period',
    //     'type_scoring',
    // ];

    protected $casts = [
        'data_input' => 'array',
    ];
    
    public function eventCommonity(){
        return $this->belongsTo(Community::class, 't_community_id');
    }

    public function membersEvent(){
        return $this->belongsToMany(User::class, 't_member_event', 't_event_id', 't_user_id')->withPivot('data_input');//->where('approve', 2);
    }

    public function joinBy($userId)
    {
        return $this->membersEvent()->where('t_user_id', $userId)->exists();
    }
    
    public function albumEvent(){
        return $this->hasMany(AlbumCommonity::class,'t_event_id');
    }

    public function sponsorEvent(){
        return $this->hasMany(SponsorCommonity::class, 't_event_id');
    }

    public function city(){
        return $this->belongsTo(MasterCity::class, 'm_city_id');
    }

    public function winnerCategory(){
        return $this->hasMany(WinnerCategoryEvent::class, 't_event_id');
    }

    public function eventWinnerCat(){
        return $this->BelongsToMany(User::class, 't_winner_category_event', 't_event_id', 't_user_id');
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

    // public function typeScore(){
    //     return $this->BelongsTo(MasterConfiguration::class, 'type_scoring')->where('parameter', 'm_type_scor');
    // }

    public function periode(){
        return $this->BelongsTo(MasterConfiguration::class, 'period')->where('parameter', 'm_period');
    }

    public function rule()
    {
        return $this->belongsTo(MasterRules::class, 'type_scoring', 'id');
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

    public static function updatePeriode()
    {
        DB::beginTransaction();
        try {
            $now = Carbon::now()->format('Y-m-d');
            // Chunking for ongoing events
            DB::table('t_event')
                ->whereDate('play_date_start', '=', $now)->where('period', 1)->whereNotIn('period', [3, 4])
                ->chunkById(100, function ($events) {
                    foreach ($events as $event) {
                        DB::table('t_event')
                            ->where('id', $event->id)
                            ->update(['period' => 2]);
                    }
                });

            // Chunking for passed events
            DB::table('t_event')
                ->whereDate('play_date_end', '<', $now)->where('period', 2)->whereNotIn('period', [3, 4])
                ->chunkById(100, function ($events) {
                    foreach ($events as $event) {
                        DB::table('t_event')
                            ->where('id', $event->id)
                            ->update(['period' => 3]);
                    }
                });

            DB::commit();
            return true;
        } catch(\Throwable $e) {
            DB::rollback();
            \Log::error("Error in updating periode: ". $e);
        }

    }

    public function scopeActive(Builder $query)
    {
        $query->where('active', '1');
    }

    public function scopeFilter($query, $request)
    {
        // if ($request->has('search')) {
        //     $key = $request->search;
        // }

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
                                $val = explode(',', $val);
                                $query->whereIn('t_community_id', $val);
                                break;
                            case 'location':
                                $query->where('location', 'ilike', '%' . $val . '%');
                                break;
                            case 'type_scoring':
                                $val = explode(',', $val);
                                $query->whereIn('type_scoring', $val);
                                break;
                            case 'play_date':
                                $query->whereDate('play_date', $val);
                                break;
                            case 'period':
                                $val = explode(',', $val);
                                $query->whereIn('period', $val);
                                break;
                            case 'region':
                                $val = explode(',', $val);
                                $query->whereIn('m_city_id', $val);
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

    public function scopeFilterWeb($query, $request)
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
                            case 'community':
                                $query->whereHas('eventCommonity', function($q) use($val) {
                                    $q->where('title', 'ilike', '%' . $val . '%');
                                });
                                break;
                            case 'type_scoring':
                                $query->whereHas('typeScore', function($q) use($val) {
                                    $q->where('value1', 'ilike', '%' . $val . '%');
                                });
                                break;
                            case 'start_date':
                                $query->whereDate('play_date_start', $val);
                                break;
                            case 'period':
                                $query->whereHas('periode', function($q) use($val) {
                                    $q->where('value1', 'ilike', '%' . $val . '%');
                                });
                                break;
                            case 'region':
                                $query->whereHas('city', function($q) use($val) {
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
        $configs = new MasterConfiguration;
        $masterCity = MasterCity::where('is_staging', '1')->get();

        $typeScor = $configs->where('parameter', 'm_type_scor')->get();
        $period = $configs->where('parameter', 'm_period')->get();

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
            'Play Date' => 'date',
            'Period' => $arr2,
            'Region' => $arr3,
        ]);
    }

    public static function columnsWeb()
    {
        return Helper::columns([
            'Title' => 'string',
            'Description' => 'string',
            'Community' => 'integer',
            'Type Scoring' => 'string',
            'Start Date' => 'date',
            'Period' => 'string',
            'Region' => 'string',
        ]);
    }
}
