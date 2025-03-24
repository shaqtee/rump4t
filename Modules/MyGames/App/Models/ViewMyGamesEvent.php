<?php

namespace Modules\MyGames\App\Models;

use App\Services\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Modules\Masters\App\Models\MasterConfiguration;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\MyGames\Database\factories\ViewMyGamesEventFactory;

class ViewMyGamesEvent extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = "view_my_games_event_user";
    
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
                            case 'name':
                                $query->where('id', auth()->user()->id)->where('title', 'ilike', '%' . $val . '%');
                                break;
                            case 'event_id':
                                $query->where('id', auth()->user()->id)->where('event_id', $val);
                                break;
                            case 'title_event':
                                $query->where('id', auth()->user()->id)->where('title_event', 'ilike', '%' . $val . '%');
                                break;
                            case 'month':
                                $val = explode(',', $val);
                                $query->where('id', auth()->user()->id)->whereIn(DB::raw('EXTRACT(MONTH FROM "play_date_event")'), $val);
                                break;
                            case 'type_scoring':
                                $val = explode(',', $val);
                                $query->where('id', auth()->user()->id)->whereIn('flag_type_scoring', $val);
                                break;
                            case 'periode':
                                $val = explode(',', $val);
                                $query->where('id', auth()->user()->id)->whereIn('flag_periode', $val);
                                break;
                            case 'status':
                                $val = explode(',', $val);
                                $query->where('id', auth()->user()->id)->whereIn('status', $val);
                                break;
                            default:
                                $query->where('id', auth()->user()->id)->where($key, 'ilike', '%' . $val . '%');
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
        $status = $configs->where('parameter', 'm_status_approve')->get();

        foreach ($typeScor as $ts) {
            $arr1[$ts->value1] = $ts->value2;
        }

        foreach ($period as $p) {
            $arr2[$p->value1] = $p->value2;
        }

        foreach ($status as $s) {
            $arr3[] = $s->value1;
        }

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

        return Helper::columns([
            
            'Name' => 'string',
            'Event Id' => 'string',
            'Title Event' => 'string',
            'month' => $month,
            'Type Scoring' => $arr1,
            'Periode' => $arr2,
            'Status' => $arr3,
        ]);
    }
}
