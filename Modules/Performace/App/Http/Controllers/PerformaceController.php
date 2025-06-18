<?php

namespace Modules\Performace\App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\ApiResponse;
use Illuminate\Http\Response;
use App\Services\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Services\Functions\SqlFunction;
use Modules\Performace\App\Models\ScoreHandicap;

class PerformaceController extends Controller
{
    protected $api;
    protected $helper;
    protected $sh;
    protected $sp;
    protected $users;

    public function __construct(ApiResponse $api, Helper $helper, ScoreHandicap $sh, SqlFunction $sp, User $users)
    {
        $this->api = $api;
        $this->helper = $helper;
        $this->sh = $sh;
        $this->sp = $sp;
        $this->users = $users;
    }

    public function box($id = null){
        try {
            $user = Auth::user();
            $id = !empty($id) ? $id : $user->id;
            $datas = $this->sh->where('t_user_id', $id)
                            ->with([
                                'golfCourse' => function($q) {
                                    $q->select('id', 'name', 'course_rating', 'slope_rating');
                                }
                            ])
                            ->get();

            if($datas->count() > 0){
                $minTotRounds = 5;
                $hdConst = 113; // menghitung handicap diff
                $hdConst2 = 0.96; // menghitung handicap index
                $totRounds = $datas->count(); // Rounds
                $sumScore = $datas->sum('gross_score');
                $ags = floor(($sumScore / $totRounds) * 10) / 10; //Adjusted Gross Score
                $handicapDiff = collect();
                foreach($datas as $d) {
                    // // $nilai = ($ags - round($d->golfCourse->course_rating, 1)) * $hdConst / intval($d->golfCourse->slope_rating);
                    // $nilai = ($ags - round($d->golfCourse->course_rating, 1)) * $hdConst / intval($d->golfCourse->slope_rating);
                    // $handicapDiff->push($nilai);
                    if ($d->golfCourse && $d->golfCourse->course_rating !== null && $d->golfCourse->slope_rating != 0) {
                        $courseRating = round((float)$d->golfCourse->course_rating, 1);
                        $slopeRating = intval($d->golfCourse->slope_rating);
                        $nilai = ($ags - $courseRating) * $hdConst / $slopeRating;
                        $handicapDiff->push($nilai);
                    }
                }
                $nilaiSortDesc = $handicapDiff->sort();
                $totNilaiHandicapDiff = null; //total nilai hadicap yg diambil
                if($totRounds == 5){
                    $totNilaiHandicapDiff = 1;
                } else if($totRounds == 8) {
                    $totNilaiHandicapDiff = 2;
                } else if($totRounds == 10) {
                    $totNilaiHandicapDiff = 3;
                } else if($totRounds == 20) {
                    $totNilaiHandicapDiff = 10;
                } else if($totRounds >= 20) {
                    $totNilaiHandicapDiff = 10;
                }

                $handicapDiffTerbaik = array_slice($nilaiSortDesc->toArray(), 0, $totNilaiHandicapDiff);
                $handicapDiffTerbaik = floor($handicapDiffTerbaik[0] * 10) / 10;
                $handicapIndex = floor(($handicapDiffTerbaik * $hdConst2) * 10) / 10;
                // $handicapIndex = round(round($handicapDiffTerbaik[0], 1) * $hdConst2, 1);


                if($totRounds < $minTotRounds){
                    $handicapIndex = "N/A";
                }

                $datas = [
                    'avgScore' => $ags,
                    'rounds' => $totRounds,
                    'handicapIndex' => $handicapIndex,
                ];
            } else {
                $datas = [
                    'avgScore' => null,
                    'rounds' => null,
                    'handicapIndex' => "N/A",
                ];
            }

            return $this->api->success($datas);
        } catch(\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function index(){
        try {
            $user = Auth::user();
            $year = $request->year ?? Carbon::now()->format('Y');
            $datas = $this->users->select('id', 'name')->with([
                'MyScore' => function($q) use($year) {
                    $q->select('id', 't_user_id', 't_event_id', 't_lets_play_id', 't_course_id', 'gross_score', 'created_at as input_date')
                        ->with([
                            'event' => function($q) {
                                $q->select('id', 'title', 'type_scoring', 'play_date_start', 'period', 'm_round_type_id')->with(['roundType:id,value1']);
                            },
                            'letsPlay' => function($q) {
                                $q->select('id', 'title', 'play_date', 'm_type_scor_id', 'm_round_type_id')->with(['roundType:id,value1']);
                            },
                            'golfCourse:id,name,number_par'
                        ])->whereYear('created_at', $year)->orderBy('created_at', 'desc')->take(5);
                },
                ])->orderBy('created_at', 'desc')->findOrfail($user->id);

            $data = $datas['MyScore'];
            // dd($data);
            $data->transform(function ($item) {
                if ($item->golfCourse && $item->golfCourse->number_par) {
                    $item->to_par = $item->gross_score - $item->golfCourse->number_par;
                } else {
                    $item->to_par = null;
                }
                return $item;
            });

            return $this->api->success($data);
        } catch(\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function viewAll(Request $request){
        try {
            $user = Auth::user();
            $year = $request->year ?? Carbon::now()->format('Y');
            $datas = $this->users->select('id', 'name')->with([
                'MyScore' => function($q) use($year){
                    $q->select('id', 't_user_id', 't_event_id', 't_lets_play_id', 't_course_id', 'gross_score', 'created_at as input_date')
                        ->with([
                            'event' => function($q) {
                                $q->select('id', 'title', 'type_scoring', 'play_date_start', 'period', 'm_round_type_id')->with(['roundType:id,value1']);
                            },
                            'letsPlay' => function($q) {
                                $q->select('id', 'title', 'play_date', 'm_type_scor_id', 'm_round_type_id')->with(['roundType:id,value1']);
                            },
                            'golfCourse:id,name,number_par',
                        ])->groupBy('id', 't_user_id', 't_event_id', 't_lets_play_id', 't_course_id', 'gross_score', 'created_at')->whereYear('created_at', $year)->orderBy('created_at', 'desc');
                },
                ])->orderBy('created_at', 'desc')->findOrfail($user->id);

            $data = $datas['MyScore'];

            $data->transform(function ($item) {
                if ($item->golfCourse && $item->golfCourse->number_par) {
                    $item->to_par = $item->gross_score - $item->golfCourse->number_par;
                } else {
                    $item->to_par = null;
                }
                return $item;
            });

            $groupedData = $data->groupBy(function ($item) {
                $date = Carbon::parse($item->input_date);
                return $date->format('Y-n');
            });

            $emptyMonths = array_fill(1, 12, []);

            $result = [];
            foreach ($groupedData as $key => $group) {
                // $totalData = $group->count();
                [$year, $month] = explode('-', $key);
                // $result[$year]['total_data_permonth'] = $totalData;
                // $result[$year][$month] = $group->toArray();
                $result['years'] = $year;
                $result['months'][$month] = $group->toArray();
            }
// ---------------------------------------------------------
            // $groupedData = $data->groupBy(function ($item) {
            //     return Carbon::parse($item->input_date)->format('n');
            // });

            // $emptyMonths = array_fill(1, 12, []);

            // $result = array_replace_recursive($emptyMonths, $groupedData->toArray());

            return $this->api->success($result);
        } catch(\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }
}
