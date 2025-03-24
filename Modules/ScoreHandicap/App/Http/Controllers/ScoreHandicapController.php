<?php

namespace Modules\ScoreHandicap\App\Http\Controllers;

use App\Exceptions\Handler;
use Illuminate\Http\Request;
use App\Services\ApiResponse;
use App\Services\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Modules\Community\App\Models\EventCommonity;
use Modules\Community\App\Models\Hole;
use Modules\Masters\App\Models\MasterRules;
use Modules\Masters\App\Models\MasterRulesDetail;
use Modules\MyGames\App\Models\LetsPlay;
use Modules\ScoreHandicap\App\Models\ScoresDetail as ModelsScoresDetail;
use Modules\ScoreHandicap\App\Models\ScoreHandicap;
use Modules\ScoreHandicap\App\Services\Interfaces\ScoreHandicapInterface;

class ScoreHandicapController extends Controller
{
    protected $model;
    protected $interface;
    protected $helper;
    protected $handler;
    protected $api;
    protected $users;
    protected $event;
    protected $letsPlay;
    protected $scoresDetail;
    protected $rulesScore;
    protected $hole;
    protected $detailRules;


    public function __construct(ScoreHandicap $model, ScoreHandicapInterface $interface, Helper $helper, Handler $handler, ApiResponse $api, User $users, EventCommonity $event, LetsPlay $letsPlay, ModelsScoresDetail $scoresDetail, MasterRules $rulesScore, Hole $hole,  MasterRulesDetail $detailRules)
    {
        $this->model = $model;
        $this->interface = $interface;
        $this->helper = $helper;
        $this->handler = $handler;
        $this->api = $api;
        $this->users = $users;
        $this->event = $event;
        $this->letsPlay = $letsPlay;
        $this->scoresDetail = $scoresDetail;
        $this->rulesScore = $rulesScore;
        $this->hole = $hole;
        $this->detailRules = $detailRules;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $page = $request->size ?? 10;
            $index = $this->model->filter($request)->orderByDesc('id')->paginate($page);

            return $this->api->list($index, $this->model);
        } catch(\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            $request['t_user_id'] = Auth::id();
            $store = $this->interface->store($this->model, $request->all());

            $folder = "dgolf/score-handicap";
            $column = "image_score";

            $this->helper->uploads($folder, $store, $column);

            if(isset($request->t_event_id) || $request->t_event_id != null){
                $event = $this->event->with([
                    'membersEvent' => function($q) {
                        $q->where('t_member_event.approve', 'PAID')->whereNotNull('users.fcm_token');
                    }
                ])->find($request->t_event_id);

                $FcmToken = collect();
                foreach($event->membersEvent as $getFcmToken) {
                    $map = $getFcmToken->fcm_token;

                    $FcmToken->push($map);
                }
                $this->helper->pushNotification2($FcmToken->toArray(), "Informasi Event", "Leaderboard Telah Ditambahkan Pada $event->title", null, 'EVENT', $event->id);
            }
            DB::commit();
            return $this->api->success($store, "Data has been added");
        } catch(\Throwable $e) {
            DB::rollBack();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function storeV2(Request $request)
    {
        // dd($request->keys());
        DB::beginTransaction();
        try{
            $request['t_user_id'] = Auth::id();
            $store = $this->interface->store($this->model, $request->all());

            $folder = "dgolf/score-handicap";
            $column = "image_score";

            $this->helper->uploads($folder, $store, $column);

            if($request->flag_data == 't_event')
            {
                if(isset($request->t_event_id) || $request->t_event_id != null){
                    $event = $this->event->with([
                        'membersEvent' => function($q) {
                            $q->where('t_member_event.approve', 'PAID')->whereNotNull('users.fcm_token');
                        }
                    ])->find($request->t_event_id);

                    $FcmToken = collect();
                    foreach($event->membersEvent as $getFcmToken) {
                        $map = $getFcmToken->fcm_token;

                        $FcmToken->push($map);
                    }
                    $this->helper->pushNotification2($FcmToken->toArray(), "Informasi Event", "Leaderboard Telah Ditambahkan Pada $event->title", null, 'EVENT', $event->id);
                }
            } elseif ($request->flag_data == 't_lets_play') {
                if(isset($request->t_lest_play_id) || $request->t_lest_play_id != null){
                    $letsPlay = $this->letsPlay->with([
                        'memberLetsPlay' => function($q) {
                            $q->where('t_member_lets_play.approve', 'ACCEPT')->whereNotNull('users.fcm_token');
                        }
                    ])->find($request->t_lest_play_id);
                }
            }

            DB::commit();
            return $this->api->success($store, "Data has been added");
        } catch(\Throwable $e) {
            DB::rollBack();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function storeScoresDetails(Request $request) {
        DB::beginTransaction();
        try{

            $request['user_id'] = Auth::id();
            // $scoresDetails = $this->interface->store($this->scoresDetail, $request->all());
            $detailScore = [];

            if($request->flag_data == 't_event')
                {
                    $model = $this->model->where('t_event_id', $request->event_id)->where('t_user_id', Auth::id())->first();
                    $course_id = $request->course_id;
                    $totalStroke = 0;

                        foreach ($request->scores_per_hole as $holeId => $score) {
                            $holeNumber = $score['hole_id'] ?? null;

                            // pakai ini jika ga ada course_idnya kalo ada langsung pakai
                            $course = DB::table('t_event')
                            ->join('m_golf_course', 't_event.m_golf_course_id', '=', 'm_golf_course.id')
                            ->where('t_event.id', $request->event_id)
                            ->select('m_golf_course.id as course_id', 'm_golf_course.name as course_name')
                            ->first();

                            $holeId = Hole::where('hole_number', $holeNumber)
                            ->where('course_id', $course->course_id)
                            ->value('id');

                            $createdScore = $this->scoresDetail->create([
                                'user_id' => Auth::id(),
                                'event_id' => $request->event_id,
                                'hole_id' => $holeId,
                                'stroke' => $score['stroke'] ?? null,
                                'putts' => $score['putts'] ?? null,
                                'sand_shots' => $score['sand_shots'] ?? null,
                                'penalties' => $score['penalties'] ?? null,
                                'fairways' => $score['fairways'] ?? null,
                                'image_score' => $request->image_score
                            ]);

                            $detailScore[] = $createdScore;
                            $totalStroke += $score['stroke'] ?? 0;
                        }

                        $model->update(['gross_score' => $totalStroke]);
                        $model->update(['image_score' => $request->image_score]);

            }elseif ($request->flag_data == 't_lets_play') {

                $model = $this->model->where('t_lets_play_id', $request->lets_play_id)->where('t_user_id', Auth::id())->first();
                $course_id = $request->course_id;
                $totalStroke = 0;

                    foreach ($request->scores_per_hole as $holeId => $score) {
                        $holeNumber = $score['hole_id'] ?? null;

                        $holeId = Hole::where('hole_number', $holeNumber)
                        ->where('course_id', $course_id)
                        ->value('id');

                        $createdScore = $this->scoresDetail->create([
                            'user_id' => Auth::id(),
                            'hole_id' => $holeId,
                            'stroke' => $score['stroke'] ?? null,
                            'putts' => $score['putts'] ?? null,
                            'sand_shots' => $score['sand_shots'] ?? null,
                            'penalties' => $score['penalties'] ?? null,
                            'fairways' => $score['fairways'] ?? null,
                            'lets_play_id' => $request->lets_play_id,
                            'image_score' => $request->image_score
                        ]);

                        $detailScore[] = $createdScore;
                        $totalStroke += $score['stroke'] ?? 0;
                    }

                    $model->update(['gross_score' => $totalStroke]);
                    $model->update(['image_score' => $request->image_score]);

            }
        DB::commit();
        return $this->api->success($detailScore, "Scores have been added");
        } catch (\Throwable $e) {
            DB::rollBack();
            // Tangani error
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            }
        }
    }

    // public function hitungHandicapPeoria(Request $request) {
    //     DB::beginTransaction();
    //     try {

    //         $request['user_id'] = Auth::id();

    //         $event = $this->event->where('id', $request->event_id)->first();
    //         $model = $this->model->where('t_user_id', Auth::id())->where('t_event_id', $request->event_id)->first();
    //         $holeTerpilih = $this->detailRules->select('holes')->where('id_rules', $event->type_scoring)->get();
    //         $masterHoles = $this->hole->whereIn('hole_number', $holeTerpilih)->get()->keyBy('hole_number');


    //         $holeIds = $masterHoles->pluck('id')->toArray();
    //         $detailScores = $this->scoresDetail
    //             ->where('event_id', $request->event_id)
    //             ->where('user_id', Auth::id())
    //             ->whereIn('hole_id', $holeIds)
    //             ->get();

    //         $totalSkorNetto = 0;

    //         // Hitung total skor netto
    //         $totalSkorNetto = 0;
    //         foreach ($detailScores as $score) {
    //             $parHole = $masterHoles->firstWhere('id', $score->hole_id)?->par ?? 0;

    //             if ($score->stroke <= $parHole) {
    //                 $skorNetto = $score->stroke; // Jika stroke <= par, skor = stroke
    //             } else {
    //                 $skorNetto = $parHole + 3; // Jika stroke > par, skor = par + 3
    //             }

    //             $totalSkorNetto += $skorNetto;
    //         }

    //         $courseId = $masterHoles->first()->course_id;

    //         $jumlahHole = count($holeTerpilih);
    //         $parLapangan = DB::table('m_golf_course')->where('id', $courseId)->value('number_par');

    //         if ($jumlahHole <= 6) {
    //             $handicapSementara = (($totalSkorNetto * 3) - $parLapangan) * 0.8;
    //         } elseif ($jumlahHole >= 12) {
    //             $handicapSementara = (($totalSkorNetto * 1.5) - $parLapangan) * 0.8;
    //         }

    //         $model->update(['temp_handicap' => $handicapSementara]);

    //     DB::commit();
    //     return response()->json(['handicap_sementara' => $handicapSementara]);
    //     } catch (\Throwable $e) {
    //         DB::rollBack();
    //     }
    // }

    public function hitungHandicapPeoria(Request $request) {
        DB::beginTransaction();
        try {

            $request['user_id'] = $request->user_id;

            $event = $this->event->with('rule')->where('id', $request->event_id)->first();
            $typeScoring = $event->rule->select('name', 'id')->first();
            $model = $this->model->where('t_user_id', $request->user_id)->where('t_event_id', $request->event_id)->first();
            $holeTerpilih = $this->detailRules->select('holes')->where('id_rules', $event->type_scoring)->get();
            $masterHoles = $this->hole->whereIn('hole_number', $holeTerpilih)->get()->keyBy('hole_number');


            $holeIds = $masterHoles->pluck('id')->toArray();
            $detailScores = $this->scoresDetail
                ->where('event_id', $request->event_id)
                ->where('user_id', $request->user_id)
                ->whereIn('hole_id', $holeIds)
                ->get();

            // get total score selisih stroke - par per hole
            $totalScore = 0;
            foreach ($detailScores as $score) {
                $parHole = $masterHoles->firstWhere('id', $score->hole_id)?->par ?? 0;
                $totalScore += ($score->stroke - $parHole);
            }

            $handicap = 0;
            $net = 0;
            $gross = $model->gross_score;

                if(stripos($typeScoring->name, 'peoria') !== false){

                    // perhitungan preoria
                    $handicap = round($totalScore * 1.2);
                    $net = round($gross - $handicap);

                }elseif(stripos($typeScoring->name, 'system 36') !== false || stripos($typeScoring->name, 'system36') !== false){

                    // perhitungan system36
                    $handicap = round(36 - $totalScore);
                    $net = round($gross - $handicap);

                }

            $model->update([
                'temp_handicap' => $handicap,
                'net_score' => $net,
            ]);

        DB::commit();
        return response()->json(['handicap_sementara' => $handicap ,'net_score' => $net]);
        } catch (\Throwable $e) {
            DB::rollBack();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            }
        }
    }

    // public function hitungHandicapSystme36(Request $request) {
    //     DB::beginTransaction();
    //     try {

    //         $request['user_id'] = Auth::id();

    //         $event = $this->event->where('id', $request->event_id)->first();
    //         $typeScoring = $event->join('t_rules', 't_rules.id', '=', 't_event.type_scoring')
    //                         ->select('t_rules.name', 't_event.type_scoring')
    //                         ->first();
    //         $model = $this->model->where('t_user_id', Auth::id())->where('t_event_id', $request->event_id)->first();
    //         $holeTerpilih = $this->detailRules->select('holes')->where('id_rules', $event->type_scoring)->get();
    //         $masterHoles = $this->hole->whereIn('hole_number', $holeTerpilih)->get()->keyBy('hole_number');

    //         $holeIds = $masterHoles->pluck('id')->toArray();
    //         $detailScores = $this->scoresDetail
    //             ->where('event_id', $request->event_id)
    //             ->where('user_id', Auth::id())
    //             ->whereIn('hole_id', $holeIds)
    //             ->get();

    //         $totalScore = 0;
    //         foreach ($detailScores as $score) {
    //             $parHole = $masterHoles->firstWhere('id', $score->hole_id)?->par ?? 0;
    //             $totalScore += ($score->stroke - $parHole);
    //         }

    //         $handicapSystem36 = 36 - $totalScore;

    //         $gross = $this->model->value('gross_score');
    //         $net = $gross - $handicapSystem36;

    //         $model->update([
    //             'temp_handicap' => $handicapSystem36,
    //             'net_score' => $net,
    //         ]);

    //     DB::commit();
    //     return response()->json(['handicap_sementara' => $handicapSystem36 ,'net_score' => $net]);
    //     } catch (\Throwable $e) {
    //         DB::rollBack();
    //     }
    // }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        try{
            $show = $this->model->findorfail($id);

            if (!$show) {
                return $this->api->error("Data Not Found");
            }

            return  $this->api->success($show,  "Success to get data");
        } catch(\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try{
            $update = $this->interface->update($this->model, $request->all(), $id);

            DB::commit();
            return  $this->api->success($update,  "Update Successfully");
        } catch(\Throwable $e) {
            DB::rollBack();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $delete = $this->interface->destroy($this->model, $id);

            DB::commit();
            return  $this->api->delete($delete,  "Delete Successfully");
        } catch(\Throwable $e) {
            DB::rollback();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function bulk_destroy(Request $request)
    {
        DB::beginTransaction();
        try {
            $bulkDestroy = $this->interface->destroyBulk($this->model, $request->all());

            DB::commit();
            return  $this->api->delete($bulkDestroy);
        } catch(\Throwable $e) {
            DB::rollback();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function my_list_event(){
        try {
            $userId = Auth::id();
            $index = $this->users->select('id', 'name')
                        ->with([
                            'myEventList' => function($q) {
                                $q->select('t_event.id', 't_event.t_community_id', 't_event.title', 't_event.type_scoring', 't_event.play_date_start', 't_event.t_tee_man_id', 't_event.t_tee_ladies_id', 't_event.m_golf_course_id', 't_event.m_round_type_id')
                                ->with(['teeMan:id,tee_type,course_rating,slope_rating', 'teeLadies:id,tee_type,course_rating,slope_rating', 'golfCourseEvent:id,name', 'roundType:id,value1'])->whereNot('t_event.period', 1)->where('t_member_event.approve', 'PAID')
                                ->whereNotExists(function($subQuery) {
                                    $subQuery->select(DB::raw(1))
                                        ->from('t_score_handicap')
                                        ->whereRaw('t_score_handicap.t_event_id = t_event.id')
                                        ->whereRaw('t_score_handicap.t_user_id = ' . Auth::id());
                                });
                            }
                        ])
                        ->findOrfail($userId);

            // $index = $this->event->whereHas('membersEvent', function($q) {
            //     $q->where('t_member_event.t_user_id', Auth::user()->id)->where('t_member_event.approve', 'PAID')
            //         ->whereNotExists(function($subQuery) {
            //             $subQuery->select(DB::raw(1))
            //                 ->from('t_score_handicap')
            //                 ->whereRaw('t_score_handicap.t_event_id = t_event.id');
            //         });
            // })->whereNot('period', 1)->with(['teeMan', 'teeLadies', 'golfCourseEvent', 'roundType'])->get();

            return $this->api->success($index);
        } catch(\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function my_list_lets_play(){
        try {
            $userId = Auth::id();
            $index = $this->users->select('id', 'name')
                        ->with([
                            'myLetsPlayList' => function($q) {
                                $q->with(['golfCourse:id,name', 'teeBox:id,tee_type', 'roundType:id,value1'])
                                ->whereNotExists(function($subQuery) {
                                    $subQuery->select(DB::raw(1))
                                        ->from('t_score_handicap')
                                        ->whereRaw('t_score_handicap.t_lets_play_id = t_lets_play.id');
                                });
                            }
                        ])
                        ->findOrfail($userId);

            // $index = $this->letsPlay->whereHas('memberLetsPlay', function($q) {
            //     $q->where('t_member_lets_play.t_user_id', Auth::user()->id)
            //         ->whereNotExists(function($subQuery) {
            //             $subQuery->select(DB::raw(1))
            //                 ->from('t_score_handicap')
            //                 ->whereRaw('t_score_handicap.t_lets_play_id = t_lets_play.id');
            //         });
            // })->with(['golfCourse:id,name', 'teeBox:id,value1', 'roundType:id,value1'])->get();

            return $this->api->success($index);
        } catch(\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function my_list_all_games(){
        try {
            $userId = Auth::id();
            $index = $this->users->select('id', 'name')
                        ->with([
                            'myEventList' => function($q) {
                                $q->select('t_event.id', 't_event.t_community_id', 't_event.title', 't_event.type_scoring', 't_event.play_date_start', 't_event.t_tee_man_id', 't_event.t_tee_ladies_id', 't_event.m_golf_course_id', 't_event.m_round_type_id')
                                ->with(['teeMan:id,tee_type,course_rating,slope_rating', 'teeLadies:id,tee_type,course_rating,slope_rating', 'golfCourseEvent:id,name', 'roundType:id,value1'])
                                ->whereNot('t_event.period', 1)->where('t_member_event.approve', 'PAID')
                                ->whereNotExists(function($subQuery) {
                                    $subQuery->select(DB::raw(1))
                                        ->from('t_score_handicap')
                                        ->whereRaw('t_score_handicap.t_event_id = t_event.id')
                                        ->whereRaw('t_score_handicap.t_user_id = ' . Auth::id());
                                })
                                ;
                            },
                            'myLetsPlayList' => function($q) {
                                $q->with(['golfCourse:id,name', 'teeBox', 'roundType:id,value1'])
                                ->whereNotExists(function($subQuery) {
                                    $subQuery->select(DB::raw(1))
                                        ->from('t_score_handicap')
                                        ->whereRaw('t_score_handicap.t_lets_play_id = t_lets_play.id')
                                        ->whereRaw('t_score_handicap.t_user_id = ' . Auth::id());
                                })
                                ;
                            }

                        ])
                        ->findOrfail($userId);

            $dataListGames = array_merge($index->myEventList->toArray(),$index->myLetsPlayList->toArray());
            unset($index->myEventList,$index->myLetsPlayList);
            $index->getListAllGames = $dataListGames;
            return $this->api->success($index);
        } catch(\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }
}
