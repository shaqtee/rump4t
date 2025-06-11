<?php

namespace App\Http\Controllers\Admin\Modules;

use Carbon\Carbon;
use App\Models\User;
use App\Mail\EReceipt;
use GoogleMaps\GoogleMaps;
use App\Exceptions\Handler;
use App\Mail\SuccessPayment;
use Illuminate\Http\Request;
use App\Services\WebRedirect;
use App\Services\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Modules\Community\App\Models\Community;
use Modules\Community\App\Models\MemberEvent;
use Illuminate\Validation\ValidationException;
use Modules\Community\App\Models\CourseArea;
use Modules\Community\App\Models\EventCommonity;
use Modules\Community\App\Models\Hole;
use Modules\Masters\App\Models\MasterGolfCourse;
use Modules\Performace\App\Models\ScoreHandicap;
use Modules\Masters\App\Models\MasterConfiguration;
use Modules\Masters\App\Models\MasterWinnerCategory;
use Modules\Community\App\Models\WinnerCategoryEvent;
use Modules\Masters\App\Models\MasterRules;
use Modules\Masters\App\Models\MasterRulesDetail;

class EventCommunityController extends Controller
{
    protected $model;
    protected $helper;
    protected $handler;
    protected $web;
    protected $community;
    protected $config;
    protected $gMaps;
    protected $memberEvent;
    protected $golfCourse;
    protected $masterWC;
    protected $winnerCategory;
    protected $scoreHandicap;
    protected $user;
    protected $rules;
    protected $ruleDetails;

    public function __construct(EventCommonity $model, Helper $helper, Handler $handler, WebRedirect $web, Community $community, MasterConfiguration $config, GoogleMaps $gMaps, MemberEvent $memberEvent, MasterGolfCourse $golfCourse, MasterWinnerCategory $masterWC, WinnerCategoryEvent $winnerCategory, ScoreHandicap $scoreHandicap, User $user, MasterRules $rules, MasterRulesDetail $ruleDetails)
    {
        $this->model = $model;
        $this->helper = $helper;
        $this->handler = $handler;
        $this->web = $web;
        $this->community = $community;
        $this->config = $config;
        $this->gMaps = $gMaps;
        $this->memberEvent = $memberEvent;
        $this->golfCourse = $golfCourse;
        $this->masterWC = $masterWC;
        $this->winnerCategory = $winnerCategory;
        $this->scoreHandicap = $scoreHandicap;
        $this->user = $user;
        $this->rules = $rules;
        $this->ruleDetails = $ruleDetails;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try{
            $page = $request->size ?? 10;
            $data = [
                'content' => 'Admin/EventGolf/index',
                'title' => 'Data Event Golf',
                'event' =>  $this->model->with(['eventCommonity', 'golfCourseEvent'])->filterWeb($request)->orderByDesc('id')->paginate($page)->appends($request->all()),
                'columns' => $this->model->columnsWeb(),
            ];
            return view('Admin.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function index_community(Request $request)
    {
        try{
            $page = $request->size ?? 10;
            $data = [
                'content' => 'Admin/Community/Event/index',
                'title' => 'Data Event',
                'events' =>  $this->model->with(['eventCommonity', 'golfCourseEvent'])->filterWeb($request)->orderByDesc('id')->paginate($page)->appends($request->all()),
                'columns' => $this->model->columnsWeb(),
            ];
            return view('Admin.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function show_community(string $event_id)
    {
        try{
            $data = [
                'content' => 'Admin/Community/Event/show',
                'title' => 'Show Event',
                'event' => $this->model->findOrfail($event_id),
                'community' => $this->community->get(),
                // 'type_scoring' => $this->config->where('parameter', 'm_type_scor')->get(),
                'type_scoring' => $this->rules->get(),
                'period' => $this->config->where('parameter', 'm_period')->get(),
                'golfCourse' => $this->golfCourse->where('is_staging', 1)->get(),
                'master_wc' => $this->masterWC->get(),
                'winner_category' => $this->winnerCategory->where('t_event_id', $event_id)->get()->toArray(),
                'tee_box' => DB::table('t_tee_box_course')->get(),
                'holes' => $this->config->where('parameter', 'm_round_type')->get(),
            ];
            return view('Admin.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try{
            $data = [
                'content' => 'Admin/EventGolf/addEdit',
                'title' => 'Create Event Golf',
                'event' => null,
                'community' => $this->community->get(),
                // 'type_scoring' => $this->config->where('parameter', 'm_type_scor')->get(),
                'type_scoring' => $this->rules->get(),
                'period' => $this->config->where('parameter', 'm_period')->get(),
                'golfCourse' => $this->golfCourse->select('id', 'name')->with(['teeCourse:id,t_golf_course_id,tee_type,course_rating,slope_rating'])->where('is_staging', 1)->get(),
                'master_wc' => $this->masterWC->get(),
                'holes' => $this->config->where('parameter', 'm_round_type')->get(),
                'autoPeoria' => false,
                'defaultPeoria' => 5,
            ];
            // dd($data);
            return view('Admin.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try{

            if($request->auto_scoring !== 'on')
            {
                $request['type_scoring'] = $request->type_scoring_show;
            }

            // cek apakah hole course ini lengkap atau tidak
            $roundTypeConfig = MasterConfiguration::where('parameter', 'm_round_type')
                ->where('id', $request->m_round_type_id)
                ->first();

            if (!$roundTypeConfig) {
                return response()->json(['message' => 'Round type configuration not found'], 404);
            }

            preg_match('/^\d+/', $roundTypeConfig->value1, $matches);
            $requiredHoleCount = isset($matches[0]) ? (int) $matches[0] : 0;

            $totalAvailableHoles = Hole::where('course_id', $request->m_golf_course_id)->count();

            // Validasi
            if ($totalAvailableHoles < $requiredHoleCount) {
                throw new \Exception("Jumlah hole tidak mencukupi. Dibutuhkan minimal {$requiredHoleCount}, tersedia hanya {$totalAvailableHoles}.");
            }
            
            $orderedAreaIds = $request->input('course_area_ids');
            $datas = $request->validate([
                // 't_community_id' => 'required',
                'title' => 'required|string',
                'image' => 'required|image|file|max:2048|mimes:jpeg,png,jpg',
                'description' => 'required|string',
                // 'city' => 'required',
                // 'location' => 'required|string',
                // 'auto_scoring' => 'required',
                'type_scoring' => 'required',
                'price' => 'required|numeric',
                'play_date_start' => 'required',
                'play_date_end' => 'required',
                'close_registration' => 'required|date',
                // 'period' => 'required',
                // 'longitude' => 'required|numeric',
                // 'latitude' => 'required|numeric',
                'active' => 'required|IN:1,0',
                'm_golf_course_id' => 'required|numeric',
                // 't_winner_category_id' => 'required|array',
                't_tee_man_id' => 'required',
                't_tee_ladies_id' => 'required',
                'm_round_type_id' => 'required',
                'nama_bank' => 'required',
                'nama_rekening' => 'required',
                'no_rekening' => 'required',
            ]);
            $datas['period'] = 1;

            $datas['auto_scoring'] = isset($request->auto_scoring) && $request->auto_scoring == 'on' ? true : false;
            $datas['course_area_ids'] = implode(',', $orderedAreaIds);

            //convert location to longitude & latitude
            // $latlng = $this->helper->gMaps($datas['location']);

            // if($latlng == false){
            //     return $this->web->error('Location Not Found');
            // }

            // $datas['longitude'] = $latlng['longitude'];
            // $datas['latitude'] = $latlng['latitude'];

            $folder = "dgolf/community/event";
            $column = "image";

            $model = $this->model->create($datas);

            if($request->auto_scoring == 'on' && $request->type_scoring)
            {
                self::update_rule_detail_auto_scoring($datas['m_golf_course_id'], $request->type_scoring);
            }

            // save data id win cat
            // $winnerData = collect();
            // foreach($datas['t_winner_category_id'] as $winner_id) {
            //     $map = [
            //         't_event_id' => $model->id,
            //         't_winner_category_id' => $winner_id,
            //     ];

            //     $winnerData->push($map);
            // }

            // $this->winnerCategory->insert($winnerData->toArray());

            $this->helper->uploads($folder, $model, $column);

            $FcmToken = User::whereNotNull('fcm_token')->pluck('fcm_token')->all();

            $this->helper->pushNotification2($FcmToken, "Segera Daftarkan Diri", " $model->title Telah Digelar Pada $model->play_date_start", $model->image, 'EVENT', $model->id);
            DB::commit();
            return $this->web->store('event.semua');
        } catch (\Throwable $e) {
            DB::rollBack();
            // dd($e->getMessage(), $e->getFile(), $e->getLine()); 
            if($e instanceof ValidationException){
                return $this->web->error_validation($e);
            }
            return $this->handler->handleExceptionWeb($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try{
            $data = [
                'content' => 'Admin/EventGolf/addEdit',
                'title' => 'Edit Event',
                'event' => $this->model->findOrfail($id),
                'community' => $this->community->get(),
                // 'type_scoring' => $this->config->where('parameter', 'm_type_scor')->get(),
                'type_scoring' => $this->rules->get(),
                'period' => $this->config->where('parameter', 'm_period')->get(),
                'golfCourse' => $this->golfCourse->select('id', 'name')->with(['teeCourse:id,t_golf_course_id,tee_type,course_rating,slope_rating'])->where('is_staging', 1)->get(),
                'master_wc' => $this->masterWC->get(),
                'winner_category' => $this->winnerCategory->where('t_event_id', $id)->get()->toArray(),
                'holes' => $this->config->where('parameter', 'm_round_type')->get(),
                'autoPeoria' => $this->model->findOrfail($id)->auto_scoring,
                'defaultPeoria' => 5,
            ];
            return view('Admin.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();
        try{

            $selectedFields = $request->input('fields'); 

            if($request->auto_scoring !== 'on')
            {
                $request['type_scoring'] = $request->type_scoring_show;
            }
            $orderedAreaIds = $request->input('course_area_ids');

            $datas = $request->validate([
                // 't_community_id' => 'required',
                'title' => 'required|string',
                'image' => 'nullable|image|file|max:2048|mimes:jpeg,png,jpg',
                'description' => 'required|string',
                // 'city' => 'required',
                // 'location' => 'required|string',
                // 'auto_scoring' => 'required',
                'type_scoring' => 'required',
                'price' => 'required|numeric',
                'play_date_start' => 'required',
                'play_date_end' => 'required',
                'close_registration' => 'required',
                'period' => 'nullable',
                // 'longitude' => 'required|numeric',
                // 'latitude' => 'required|numeric',
                'active' => 'required|IN:1,0',
                'm_golf_course_id' => 'required|numeric',
                // 't_winner_category_id' => 'required|array',
                't_tee_man_id' => 'required',
                't_tee_ladies_id' => 'required',
                'm_round_type_id' => 'required',
                'nama_bank' => 'required',
                'nama_rekening' => 'required',
                'no_rekening' => 'required',
            ]);

            $datas['course_area_ids'] = implode(',', $orderedAreaIds);

            $datas['auto_scoring'] = isset($request->auto_scoring) && $request->auto_scoring == 'on' ? true : false;

            $datas['selected_fields'] = json_encode($selectedFields);

            //convert location to longitude & latitude
            // $latlng = $this->helper->gMaps($datas['location']);

            // if($latlng == false){
            //     return $this->web->error('Location Not Found');
            // }

            // $datas['longitude'] = $latlng['longitude'];
            // $datas['latitude'] = $latlng['latitude'];

            $folder = "dgolf/community/event";
            $column = "image";

            $model = $this->model->findOrfail($id);
            // $winners = $this->winnerCategory->where('t_event_id', $id)->get();
            // $model->winnerCategory()->delete();
            //-----------------------------------------------------------------------------------------------------------------------
            // $winnerCategory = $this->winnerCategory->select('t_winner_category_id')->where('t_event_id', $id)->get()->toArray();
            // $winnerCategory = array_column($winnerCategory, 't_winner_category_id');
            // $upWC = array_unique(array_intersect($datas['t_winner_category_id'], $winnerCategory));

            // $winnerData = collect();
            // foreach($datas['t_winner_category_id'] as $winner_id) {
            //     $userWinner = $winners->where('t_event_id', $model->id)->where('t_winner_category_id', $winner_id)->first();
            //     $map = [
            //         't_user_id' => $userWinner->t_user_id ?? null,
            //         't_event_id' => $model->id,
            //         't_winner_category_id' => $winner_id,
            //     ];

            //     $winnerData->push($map);
            // }

            $model->update($datas);

            // $this->winnerCategory->insert($winnerData->toArray());

            $this->helper->uploads($folder, $model, $column);

            DB::commit();
            return $this->web->update('event.semua');
        } catch (\Throwable $e) {
            DB::rollBack();
            if($e instanceof ValidationException){
                return $this->web->error_validation($e);
            }
            return $this->handler->handleExceptionWeb($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try{
            $this->model->findOrfail($id)->delete();
            DB::commit();
            return $this->web->destroy('event.semua');
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function index_registrant(Request $request, $event_id){
        try{
            $page = $request->size ?? 10;
            $data = [
                'content' => 'Admin/Event/listRegistrant',
                'title' => 'List Registrant',
                'members' =>  $this->memberEvent->with(['event:id,title', 'user:id,name'])->where('t_event_id', $event_id)->whereHas('user')->filter($request)->orderByDesc('id')->paginate($page)->appends($request->all()),
                'users' => $this->user->where('flag_done_profile', 1)->get(),
                'columns' => $this->memberEvent->columnsWeb(),
                'event_id' => $event_id,
            ];
            return view('Admin.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function update_registrant(Request $request, string $id)
    {
        DB::beginTransaction();
        try{
            $datas = $request->validate([
                'approve' => 'required'
            ]);
            $datas['payment_date'] = now();

            $model = $this->memberEvent->with(['user', 'event.golfCourseEvent'])->findOrfail($id);

            $model->update($datas);

            if($datas['approve'] == 'PAID'){
                $email = new SuccessPayment($model);
                Mail::to($model->user->email)->send($email);
                $email = new EReceipt($model);
                Mail::to($model->user->email)->send($email);
            }

            DB::commit();
            return $this->web->updateBack();
        } catch (\Throwable $e) {
            DB::rollBack();
            if($e instanceof ValidationException){
                return $this->web->error_validation($e);
            }
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function leaderboard($id)
    {
        try{
            $select = "users.id as t_user_id, users.name as t_user_name,
                   m_golf_course.id as m_course_id, m_golf_course.name as m_course_name, m_golf_course.number_par as m_course_num_par,
                   t_score_handicap.gross_score as gross_score, t_score_handicap.temp_handicap as handicap";
                //    t_tee_box_course.id as m_tee_id, t_tee_box_course.tee_type as m_tee_name,

            $data = DB::table('t_score_handicap')->select(DB::raw($select))
                        ->leftJoin('users', 't_score_handicap.t_user_id', '=', 'users.id')
                        ->leftJoin('m_golf_course', 't_score_handicap.t_course_id', '=', 'm_golf_course.id')
                        // ->leftJoin('m_configurations', 't_score_handicap.t_tee_id', '=', 'm_configurations.id')
                        // ->leftJoin('t_tee_box_course', 't_score_handicap.t_tee_id', '=', 't_tee_box_course.id')
                        // ->where('m_configurations.parameter', 'm_tee')
                        ->where('t_score_handicap.t_event_id', '=', $id)
                        ->orderBy('users.id', 'ASC')
                        ->get();

            $collection = collect($data);
            // dd($collection);
            $groupedData = $collection->groupBy('t_user_name');
            $result = $groupedData->map(function ($group) {
                $gross = $group->sum('gross_score');
                $coursePar = $group->first()->m_course_num_par;
                $toPar = $gross - $coursePar;
                $handicap = $group->first()->handicap ?? 0;

                return [
                    'name' => $group->first()->t_user_name,
                    'gross' => $gross,
                    'to_par' => $toPar,
                    'handicap' => $handicap
                ];
            });

            $result = $result->sortBy('gross')->values()->all();

            $data = [
                'content' => 'Admin/Event/leaderboard',
                'title' => 'Leaderboard',
                'leaderboard' => $result,
            ];

            return view('Admin.Layouts.wrapper', $data);

        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function create_winner_category($id)
    {
        try {
            $data = [
                'content' => 'Admin/Event/winnerCategory',
                'title' => 'Add Data',
                'masterWinerCategory' => $this->masterWC->orderByDesc('id')->get(),
                'event_id' => $id,
                'dataWinnerCategory' => $this->winnerCategory->with(['masterWinnerCategory:id,code,name'])->where('t_event_id', $id)->orderBy('sort', 'ASC')->get()
            ];

            return view('Admin.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function store_winner_category(Request $request)
    {
        DB::beginTransaction();
        try {
            $datas = $request->validate([
                't_event_id' => 'required|integer',
                't_winner_category_id' => 'required|integer',
                'sort' => 'required|integer',
            ]);

            $this->winnerCategory->create($datas);
            DB::commit();
            return $this->web->successBack();
        } catch (\Throwable $e) {
            DB::rollBack();
            if($e instanceof ValidationException){
                return $this->web->error_validation($e);
            }
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function create_input_score($id)
    {
        try {
            $event = $this->model->with([
                        'eventCommonity:id,title',
                        'membersEvent' => function($q){
                            $q->select('users.id', 'name', 'nickname', 'users.image', 'approve', 'voucher')->where('approve', 'PAID')->orderBy('name', 'ASC')->get();
                        },
                    ])->findOrfail($id);
            $data = [
                'content' => 'Admin/Event/inputScore',
                'title' => 'Input Score',
                'event' => $event,
                'score' => null,
            ];

            return view('Admin.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function getUserScores(Request $request)
    {
        $userId = $request->input('user_id');
        $eventId = $request->input('event_id');

        $score = DB::table('t_score_handicap')->where('t_user_id', $userId)->where('t_event_id', $eventId)->first();
        $scoresdetail = DB::table('t_scores_detail')
                ->join('t_holes', 't_scores_detail.hole_id', '=', 't_holes.id')
                ->where('t_scores_detail.event_id', $eventId)
                ->where('t_scores_detail.user_id', $userId)
                ->select('t_holes.hole_number', 't_scores_detail.*')
                ->get();

        $response = [];
        foreach (range(1, 18) as $holeNumber) {
            $holeData = $scoresdetail->firstWhere('hole_number', $holeNumber);
            $response["hole{$holeNumber}_id"] = $holeData->hole_id ?? null;
            $response["hole{$holeNumber}_stroke"] = $holeData->stroke ?? null;
            $response["hole{$holeNumber}_putts"] = $holeData->putts ?? null;
            $response["hole{$holeNumber}_sand_shots"] = $holeData->sand_shots ?? null;
            $response["hole{$holeNumber}_penalties"] = $holeData->penalties ?? null;
        }

        $response["grossScore"] = $score->gross_score ?? null;
        $response["imageScore"] = $score->image_score ?? null;

        return response()->json($response);
    }


    public function store_input_score(Request $request)
    {
        DB::beginTransaction();
        try {
            $datas = $request->validate([
                't_event_id' => 'required|integer',
                't_user_id' => 'required|integer',
                'gross_score' => 'required|integer',
                'image' => 'image',
                'holes' => 'required|array',
                'holes.*.hole_id' => 'nullable|integer',
                'holes.*.stroke' => 'nullable|integer',
                'holes.*.putts' => 'nullable|integer',
                'holes.*.sand_shots' => 'nullable|integer',
                'holes.*.penalties' => 'nullable|integer',
            ]);

            $dbScoreHandicap = $this->scoreHandicap;

            $checkScoreUser = $dbScoreHandicap->with(['user:id,name', 'event:id,title'])->where('t_user_id', $datas['t_user_id'])->where('t_event_id', $datas['t_event_id'])->first();

            $folder = "dgolf/score-handicap";
            $column = "image_score";
            $datas['image'] = !empty($datas['image']) ? $datas['image'] : '';

            if ($checkScoreUser) {

                $eventId = $datas['t_event_id'];
                $userId = $datas['t_user_id'];

                $datas['image_score'] = $datas['image'];
                unset($datas['t_event_id'], $datas['t_user_id'], $datas['image']);
                $checkScoreUser->update($datas);

                foreach ($datas['holes'] as $holeData) {
                    if (!empty($holeData['hole_id'])) {
                        DB::table('t_scores_detail')->updateOrInsert(
                            [
                                'user_id' => $userId,
                                'event_id' => $eventId,
                                'hole_id' => $holeData['hole_id'],
                            ],
                            [
                                'stroke' => $holeData['stroke'] ?? null,
                                'putts' => $holeData['putts'] ?? null,
                                'sand_shots' => $holeData['sand_shots'] ?? null,
                                'penalties' => $holeData['penalties'] ?? null,
                                'updated_at' => now(),
                            ]
                        );
                    }
                }

                $this->helper->uploads($folder, $checkScoreUser, $column);
                DB::commit();
                return $this->web->updateBack();
            }

            $event = $this->model->with([
                    'eventCommonity:id',
                    'golfCourseEvent:id,name',
                    'roundType:id,value1',
                    'teeMan:id,tee_type,course_rating,slope_rating',
            ])->findOrfail($datas['t_event_id']);

            $data = [
                't_event_id' => $datas['t_event_id'],
                't_user_id' => $datas['t_user_id'],
                'gross_score' => $datas['gross_score'],
                't_course_id' => $event->golfCourseEvent->id,
                't_course_name' => $event->golfCourseEvent->name,
                'date' => now(),
                't_tee_id' => $event->teeMan->id,
                't_tee_name' => $event->teeMan->tee_type,
                'course_rating' => $event->teeMan->course_rating,
                'slope_rating' => $event->teeMan->slope_rating,
                't_community_id' => $event->eventCommonity->id,
                'm_round_type_id' => $event->roundType->id,
                'm_round_type_name' => $event->roundType->value1,
                'image_score' => $datas['image'],
            ];



            $store = $dbScoreHandicap->create($data);
            $this->helper->uploads($folder, $store, $column);
            DB::commit();
            return $this->web->successBack();
        } catch (\Throwable $e) {
            DB::rollBack();
            if($e instanceof ValidationException){
                return $this->web->error_validation($e);
            }
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function store_user_join(Request $request)
    {
        DB::beginTransaction();
        try{
            $event = $this->model->findOrFail($request->t_event_id);
            $user = $this->user->where('flag_done_profile', 1)->findOrFail($request->t_user_id);

            if (!isset($user) || !isset($event)) {
                return $this->error("Data tidak di temukan!");
            }
            if (Carbon::now()->greaterThan($event->close_registration)) {
                return $this->web->error("Pendaftaran sudah ditutup.");
            }

            $this->memberEvent->create([
                "t_user_id" => $user->id,
                "t_event_id" => $event->id,
                "approve" => "WAITING_FOR_PAYMENT",
                "voucher" => $this->helper->codeVoucher($user->id),
                // "payment_date" => Carbon::now(),
            ]);
            DB::commit();
            return $this->web->successReturn("event.registrant.semua", "event_id", $event->id);
        } catch (\Throwable $e) {
            DB::rollBack();
            if($e instanceof ValidationException){
                return $this->web->error_validation($e);
            }
            return $this->handler->handleExceptionWeb($e);
        }
    }

    function update_rule_detail_auto_scoring($courseId, $ruleId)
    {
        $couresHoles = $this->golfCourse->with('holes')->findOrFail($courseId);
        $holes = collect($couresHoles->holes)->sortBy('hole_number')->chunk(9);
        $selectedHoles = array();
        foreach($holes as $hasGroup)
        {
            $array = $hasGroup->toArray();
            shuffle($array);
            $hasGroup = collect($array);
            $combinations = array();
            $longHole =  $hasGroup->count();

            for ($i = 0; $i < $longHole; $i++) {
                for ($j = $i + 1; $j < $longHole; $j++) {
                    for ($k = $j + 1; $k < $longHole; $k++) {
                        for ($l = $k + 1; $l < $longHole; $l++) {
                            for ($m = $l + 1; $m < $longHole; $m++) {
                                for ($n = $m + 1; $n < $longHole; $n++) {
                                    $combination = $hasGroup->slice($i, 1)->merge($hasGroup->slice($j, 1))->merge($hasGroup->slice($k, 1))
                                        ->merge($hasGroup->slice($l, 1))->merge($hasGroup->slice($m, 1))->merge($hasGroup->slice($n, 1));
                                    $parTotal = $combination->sum('par');
                                    if ($parTotal == 22) {
                                        $combinations[] = $combination;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $key = array_rand($combinations);

            $selectedHoles = array_merge($selectedHoles, $combinations[$key]->toArray());

        }

        $dataUpdate = [];
        $now = Carbon::now();
        foreach($selectedHoles as $select)
        {
            $dataUpdate[] = [
                "id_rules" => $ruleId,
                "holes" => $select['hole_number'],
                "created_at" => $now,
                "updated_at" => $now,
            ];
        }

        $this->ruleDetails->where('id_rules', $ruleId)->delete();
        $this->ruleDetails->insert($dataUpdate);

    }
}
