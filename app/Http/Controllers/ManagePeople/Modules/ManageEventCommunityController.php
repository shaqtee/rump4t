<?php

namespace App\Http\Controllers\ManagePeople\Modules;

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
use Illuminate\Support\Facades\Mail;
use Modules\Community\App\Models\Community;
use Modules\Community\App\Models\MemberEvent;
use Illuminate\Validation\ValidationException;
use Modules\Community\App\Models\EventCommonity;
use Modules\Masters\App\Models\MasterGolfCourse;
use Modules\Masters\App\Models\MasterConfiguration;
use Modules\Masters\App\Models\MasterWinnerCategory;
use Modules\Community\App\Models\WinnerCategoryEvent;
use Modules\Masters\App\Models\MasterRules;

class ManageEventCommunityController extends Controller
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
    protected $rules;
    
    public function __construct(EventCommonity $model, Helper $helper, Handler $handler, WebRedirect $web, Community $community, MasterConfiguration $config, GoogleMaps $gMaps, MemberEvent $memberEvent, MasterGolfCourse $golfCourse, MasterWinnerCategory $masterWC, WinnerCategoryEvent $winnerCategory, MasterRules $rules)
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
        $this->rules = $rules;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try{
            $page = $request->size ?? 10;
            $data = [
                'content' => 'ManagePeople/Event/index',
                'title' => 'Data Event',
                'event' =>  $this->model->with(['eventCommonity', 'golfCourseEvent'])->where('t_community_id', auth()->user()->t_community_id)->orderByDesc('id')->paginate($page),
            ];
            return view('ManagePeople.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function index_community(Request $request)
    {
        try{
            $page = $request->size ?? 10;
            $data = [
                'content' => 'ManagePeople/Community/Event/index',
                'title' => 'Data Event',
                'events' =>  $this->model->with(['eventCommonity', 'golfCourseEvent'])->where('t_community_id', auth()->user()->t_community_id)->orderByDesc('id')->paginate($page),
            ];
            return view('ManagePeople.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function show_community(string $event_id)
    {
        try{
            $data = [
                'content' => 'ManagePeople/Community/Event/show',
                'title' => 'Show Event',
                'event' => $this->model->findOrfail($event_id),
                'community' => $this->community->findOrfail(auth()->user()->t_community_id),
                // 'type_scoring' => $this->config->where('parameter', 'm_type_scor')->get(),
                'type_scoring' => $this->rules->get(),
                'period' => $this->config->where('parameter', 'm_period')->get(),
                'golfCourse' => $this->golfCourse->where('is_staging', 1)->get(),
                'master_wc' => $this->masterWC->get(),
                'winner_category' => $this->winnerCategory->where('t_event_id', $event_id)->get()->toArray(),
                'tee_box' => DB::table('t_tee_box_course')->get(),
                'holes' => $this->config->where('parameter', 'm_round_type')->get(),
            ];
            return view('ManagePeople.Layouts.wrapper', $data);
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
                'content' => 'ManagePeople/Event/addEdit',
                'title' => 'Create Event',
                'event' => null,
                // 'community' => $this->community->get(),
                // 'type_scoring' => $this->config->where('parameter', 'm_type_scor')->get(),
                'type_scoring' => $this->rules->get(),
                'period' => $this->config->where('parameter', 'm_period')->get(),
                'golfCourse' => $this->golfCourse->where('is_staging', 1)->get(),
                'master_wc' => $this->masterWC->get(),
                'tee_box' => DB::table('t_tee_box_course')->get(),
                'holes' => $this->config->where('parameter', 'm_round_type')->get(),
            ];
            return view('ManagePeople.Layouts.wrapper', $data);
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
            $datas = $request->validate([
                // 't_community_id' => 'required',
                'title' => 'required|string',
                'image' => 'required|image|file|max:2048|mimes:jpeg,png,jpg',
                'description' => 'required|string',
                // 'city' => 'required',
                // 'location' => 'required|string',
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
            $datas['t_community_id'] = auth()->user()->t_community_id;
            $datas['period'] = 1;

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
            return $this->web->store('event.manage.semua');
        } catch (\Throwable $e) {
            DB::rollBack();
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
                'content' => 'ManagePeople/Event/addEdit',
                'title' => 'Edit Event',
                'event' => $this->model->findOrfail($id),
                // 'community' => $this->community->get(),
                // 'type_scoring' => $this->config->where('parameter', 'm_type_scor')->get(),
                'type_scoring' => $this->rules->get(),
                'period' => $this->config->where('parameter', 'm_period')->get(),
                'golfCourse' => $this->golfCourse->where('is_staging', 1)->get(),
                'master_wc' => $this->masterWC->get(),
                'winner_category' => $this->winnerCategory->where('t_event_id', $id)->get()->toArray(),
                'tee_box' => DB::table('t_tee_box_course')->get(),
                'holes' => $this->config->where('parameter', 'm_round_type')->get(),
            ];
            return view('ManagePeople.Layouts.wrapper', $data);
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
            $datas = $request->validate([
                // 't_community_id' => 'required',
                'title' => 'required|string',
                'image' => 'nullable|image|file|max:2048|mimes:jpeg,png,jpg',
                'description' => 'required|string',
                // 'city' => 'required',
                // 'location' => 'required|string',
                'type_scoring' => 'required',
                'price' => 'required|numeric',
                'play_date_start' => 'required',
                'play_date_end' => 'required',
                'close_registration' => 'required|date',
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
            $datas['t_community_id'] = auth()->user()->t_community_id;

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
            $winners = $this->winnerCategory->where('t_event_id', $id)->get();
            $model->winnerCategory()->delete();
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
            return $this->web->update('event.manage.semua');
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
            return $this->web->destroy('event.manage.semua');
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function index_registrant(Request $request, $event_id){
        try{
            $page = $request->size ?? 10;
            $data = [
                'content' => 'ManagePeople/Event/listRegistrant',
                'title' => 'List Registrant',
                'members' =>  $this->memberEvent->with(['event:id,title', 'user:id,name'])->where('t_event_id', $event_id)->orderByDesc('id')->paginate($page),
            ];
            return view('ManagePeople.Layouts.wrapper', $data);
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
                'content' => 'ManagePeople/Event/leaderboard',
                'title' => 'Leaderboard',
                'leaderboard' => $result,
            ];

            return view('ManagePeople.Layouts.wrapper', $data);

        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }
}
