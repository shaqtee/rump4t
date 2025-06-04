<?php

namespace Modules\Community\App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\ApiResponse;
use App\Services\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Services\Functions\SqlFunction;
use Modules\Community\App\Emails\EVoucher;
use Modules\Community\App\Models\Community;
use Modules\Community\App\Models\MemberEvent;
use Modules\Community\App\Models\EventCommonity;
use Modules\Community\App\Services\Interfaces\CommunityInterface;

class EventCommonityController extends Controller
{
    protected $api;
    protected $model;
    protected $helper;
    protected $interface;
    protected $memberEvent;
    protected $sqlFunction;
    protected $users;

    public function __construct(ApiResponse $api, Helper $helper, CommunityInterface $interface, EventCommonity $model, MemberEvent $memberEvent, SqlFunction $sqlFunction, User $users)
    {
        $this->api = $api;
        $this->model = $model;
        $this->helper = $helper;
        $this->interface = $interface;
        $this->memberEvent = $memberEvent;
        $this->sqlFunction = $sqlFunction;
        $this->users = $users;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $page = $request->size ?? 10;
            $index = $this->model->with([
                'membersEvent' => function($q){
                    $q->select('users.id', 'users.name', 'users.image')->where('approve', 'PAID')->orderByDesc('users.id')->get();
                },
                'eventCommonity:id,title',
                'golfCourseEvent:id,name,address,latitude,longitude',
                'city:id,code,name'
                ])->active()->filter($request)->orderByDesc('id')->paginate($page);
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
        try {
            $request['period'] = 1;
            $store = $this->interface->store($this->model, $request->all());
            //save photo profile commonity
            $folder = "dgolf/community/event";
            $column = "image";
            $this->helper->uploads($folder, $store, $column);

            DB::commit();
            return $this->api->success($store, "Data has been added");
        } catch(\Throwable $e) {
            DB::rollback();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        try {
            $shows = $this->model;
            $show = $shows->with([
                        'eventCommonity:id,title',
                        'membersEvent' => function($q){
                            $q->select('users.id', 'users.player_id', 'users.name', 'users.nickname', 'users.image', 't_member_eventgolf.image as proof_payment', 't_member_eventgolf.approve', 't_member_eventgolf.voucher')->where('approve', 'PAID')->orderBy('users.name', 'ASC')->get();
                        },
                        'albumEvent' => function($q){
                            $q->select('id', 't_event_id', 'name', 'cover')->with(['photoAlbum:id,t_album_id,name,image'])->orderByDesc('id')->get();
                        },
                        'winnerCategory' => function($q){
                            $q->with(['usersWinner:id,name', 'masterWinnerCategory:id,code,name'])->orderBy('sort', 'ASC')->get();
                        },
                        'sponsorEvent' => function($q){
                            $q->with(['socialMedia'])->orderBy('id', 'ASC')->get();
                        },
                        'city:id,code,name',
                        'golfCourseEvent:id,name,address,latitude,longitude',
                        'teeMan:id,tee_type',
                        'teeLadies:id,tee_type'
                    ])->find($id)->toArray();

            $statusUser = $shows->with([
                                'membersEvent' => function($q){
                                    $q->select('users.id', 'users.name', 'users.image', 't_member_eventgolf.image as proof_payment', 't_member_eventgolf.approve', 't_member_eventgolf.voucher')->orderByDesc('users.id')->get();
                                },
                            ])->find($id)->toArray();

            if (!$show) {
                return $this->api->error("Data Not Found");
            }

            $show['leaderboards'] = $this->leaderboard($id);

            // if($show['leaderboards'] != [] || $show['leaderboards'] != null) {
            //     $com = $this->model->with([
            //         'membersEvent' => function($q) {
            //             $q->whereNotNull('users.fcm_token');
            //         }
            //     ])->find($id);

            // $FcmToken = collect();
            // foreach($com->membersEvent as $getFcmToken) {
            //     $map = $getFcmToken->fcm_token;

            //     $FcmToken->push($map);
            // }

            // $this->helper->pushNotification2($FcmToken->toArray(), "Leaderboard's", "Pemenang Sudah Muncul!!", null, 'EVENT', $id, 'leaderboard', $id);
            // }

            if($show['sponsor_event'] == []) {
                $suppPartner = $shows->with(['eventCommonity.sponsorCommonity.socialMedia'])->find($id);
                $show['sponsor_event'] = $suppPartner['eventCommonity']['sponsorCommonity'];
            }
            // untuk jika seandai nya tidak ada punya album atau photo
            $coverEvent = [
                "id"=> 1,
                "t_event_id"=> $id,
                "name"=> "cover event",
                "cover"=> $show['image'],
                "photo_album" => [
                    [
                        "id"=> 1,
                        "t_album_id"=> 1,
                        "name"=> "cover image",
                        "image"=> $show['image']
                    ]
                ]
            ];
            array_unshift($show['album_event'], $coverEvent);

            $memberUser = Auth::user();

            $show['registered'] = false;
            $show['user_registered'] = null;
            $show['approve'] = null;
            $show['voucher'] = null;

            // if(in_array($memberUser->id, array_column($show['members_event'], 'id'))){
            //     $show['registered'] = true;
            //     $show['user_registered'] = [
            //                 "id" => $memberUser->id,
            //                 "name" => $memberUser->name,
            //                 "gender" => $memberUser->gender,
            //                 "birth_date" => $memberUser->birth_date,
            //                 "hcp_index" => $memberUser->hcp_index,
            //                 "faculty" => $memberUser->faculty,
            //                 "batch" => $memberUser->batch,
            //                 "office_name" => $memberUser->office_name,
            //                 "address" => $memberUser->address,
            //                 "business_sector" => $memberUser->business_sector,
            //                 "position" => $memberUser->position,
            //                 "active" => $memberUser->active,
            //                 "image" => $memberUser->image,
            //             ];
            // }

            foreach ($statusUser['members_event'] as $member) {
                if ($member['id'] === $memberUser->id) {
                    if($member['approve'] == "PAID"){
                        $show['registered'] = true;
                        $show['user_registered'] = [
                                "id" => $memberUser->id,
                                "name" => $memberUser->name,
                                "gender" => $memberUser->gender,
                                "birth_date" => $memberUser->birth_date,
                                "hcp_index" => $memberUser->hcp_index,
                                "faculty" => $memberUser->faculty,
                                "batch" => $memberUser->batch,
                                "office_name" => $memberUser->office_name,
                                "address" => $memberUser->address,
                                "business_sector" => $memberUser->business_sector,
                                "position" => $memberUser->position,
                                "active" => $memberUser->active,
                                "image" => $memberUser->image,
                        ];
                    }
                    $show['approve'] = $member['approve'];
                    $show['voucher'] = $member['voucher'];
                    $show['proof_payment'] = $member['proof_payment'];
                    break; // Keluar dari loop setelah menemukan anggota yang sesuai
                }
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
        try {
            $isPassed = $this->model->find($id);

            if($isPassed->period == 3){
                return $this->api->error("Can't be updated after period end.");
            }

            $update = $this->interface->update($this->model, $request->all(), $id);
            // update photo profile commonity
            $folder = "dgolf/community/event";
            $column = "image";
            $this->helper->uploads($folder, $update, $column);

            DB::commit();
            return  $this->api->success($update,  "Update Successfully");
        } catch(\Throwable $e) {
            DB::rollback();
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

    public function join_event(Request $request)
    {
        DB::beginTransaction();
        try {
            $datas = $request->all();
            $user = User::where('id', $request->t_user_id)->exists();
            $event = $this->model->where('id', $request->t_event_id)->exists();

            if (!$user){
                return $this->api->error("User not found");
            }
            if(!$event){
                return $this->api->error("Community not found");
            }

            $memberEvent = $this->memberEvent;
            $checkJoinEvent = $memberEvent->with(['event:id,title'])->where('t_user_id', $request->t_user_id)->where('t_event_id', $request->t_event_id)->first();
            if($checkJoinEvent){
                return $this->api->error("You already joined Event ". $checkJoinEvent['event']['title']);
            }

            $datas['approve'] = 'WAITING_FOR_PAYMENT'; //WAITING_FOR_PAYMENT, PAID, CANCEL
            $datas['voucher'] = $this->helper->codeVoucher();
            $joinEvent = $memberEvent->create($datas);

            $datasEmail = $joinEvent->with([
                                        'user:id,name,gender',
                                        'event' => function($q) {
                                            $q->select('id', 'title', 't_community_id', 'type_scoring', 'play_date_start', 'm_golf_course_id', 'price', 'nama_bank', 'nama_rekening','no_rekening')->with(['eventCommonity:id,title', 'golfCourseEvent:id,name,address']);
                                        }
                                    ])->findOrfail($joinEvent->id);

            // $email = new EVoucher($datasEmail);
            // Mail::to($joinEvent->user->email)->send($email);

            DB::commit();
            return $this->api->success($joinEvent, "Success Joined Event ". $joinEvent['event']['title']);
        } catch(\Throwable $e) {
            DB::rollback();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function event_date_versi_awal()
    {
        try {
            $indexs = $this->model->get();
            $collect = collect();
            foreach($indexs as $index){
                $map = [
                    'title' => $index->title,
                    'location' => $index->location,
                    'time' => Carbon::parse($index->play_date)->format('H:i A'),
                    'date' => Carbon::parse($index->play_date)->format('d F'),
                    'play_date' => Carbon::parse($index->play_date),
                ];

                $collect->push($map);
            }

            return $this->api->success($collect, 'Success');
        } catch(\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function event_date(Request $request)
    {
        try {
            $year = Carbon::parse($request->play_date_start)->format('Y');
            $month = Carbon::parse($request->play_date_start)->format('n'); // n = 3, m = 03 format bulan
            $date = Carbon::parse($request->play_date_start)->format('j');

            $events = $this->model->with(['golfCourseEvent'])->whereYear('play_date_start', $year)->whereMonth('play_date_start', $month);

            if (strlen($request->play_date_start) <= 7) { // Check if the request contains only year and month (YYYY-MM)
                $date = null;
            }
            if($date != null){
                $events = $events->whereDay('play_date_start', $date);
            }

            $events = $events->get();

            $dateEventPlay = collect();
            $monthEventPlay = collect(); // data event

            foreach($events as $event){
                $tglEvent = Carbon::parse($event->play_date_start)->format('j');

                $bulanEvent = [
                    'id' => $event->id,
                    'title' => $event->title,
                    'location' => $event->golfCourseEvent->name. ', ' .$event->golfCourseEvent->address,
                    'time' => Carbon::parse($event->play_date_start)->format('H:i A'),
                    'date' => Carbon::parse($event->play_date_start)->format('d F'),
                    'play_date' => Carbon::parse($event->play_date_start),
                ];

                $dateEventPlay->push($tglEvent);
                $monthEventPlay->push($bulanEvent);
            }

            $dateEventPlay = $dateEventPlay->toArray(); // tanggal yang ada event nya
            $monthEventPlay = $monthEventPlay->toArray(); // tanggal yang ada event nya
            $oneMonthDate = Carbon::create($year, $month)->daysInMonth; // tanggal dalam satu bulan ditahun itu
            $tglPertama = 1; // tanggal pertama dalam bulan
            $result = [];

            for ($tanggal = $tglPertama; $tanggal <= $oneMonthDate; $tanggal++) {
                if($date == null){
                    if(in_array($tanggal, $dateEventPlay)){
                        $result[$tanggal] = [];
                        foreach ($monthEventPlay as $event) {
                            if ($event['date'] == sprintf('%02d', $tanggal) . ' ' . Carbon::parse($request->play_date_start)->format('F')) {
                                $result[$tanggal][] = $event;
                            }
                        }
                    } else {
                        $result[$tanggal] = [];
                    }
                } else {
                    for ($tanggal = $tglPertama; $tanggal <= $oneMonthDate; $tanggal++) {
                        if(in_array($tanggal, $dateEventPlay)){
                            $result[$tanggal] = [];
                            foreach ($monthEventPlay as $event) {
                                if ($event['date'] == sprintf('%02d', $tanggal) . ' ' . Carbon::parse($request->play_date_start)->format('F')) {
                                    $result[$tanggal][] = $event;
                                }
                            }
                        }
                    }
                }
            }

            $response = [
                "year" => !empty($year) ? $year : null,
                "month" => !empty($month) ? $month : null,
                "date" => !empty($result) ? $result : null,
            ];

            return $this->api->success($response, 'Success');
        } catch(\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function leaderboard($t_event_id)
    {
        try{
            $select = "users.id as t_user_id, users.name as t_user_name,
                   m_golf_course.id as m_course_id, m_golf_course.name as m_course_name, m_golf_course.number_par as m_course_num_par,
                   t_score_handicap.gross_score as gross_score, t_score_handicap.temp_handicap as handicap, t_score_handicap.net_score as netscore";
                //    t_tee_box_course.id as m_tee_id, t_tee_box_course.tee_type as m_tee_name,

            $data = DB::table('t_score_handicap')->select(DB::raw($select))
                        ->leftJoin('users', 't_score_handicap.t_user_id', '=', 'users.id')
                        ->leftJoin('m_golf_course', 't_score_handicap.t_course_id', '=', 'm_golf_course.id')
                        // ->leftJoin('m_configurations', 't_score_handicap.t_tee_id', '=', 'm_configurations.id')
                        // ->leftJoin('t_tee_box_course', 't_score_handicap.t_tee_id', '=', 't_tee_box_course.id')
                        // ->where('m_configurations.parameter', 'm_tee')
                        ->where('t_score_handicap.t_event_id', '=', $t_event_id)
                        ->orderBy('users.id', 'ASC')
                        ->get();

            $collection = collect($data);
            $groupedData = $collection->groupBy('t_user_name');
            $result = $groupedData->map(function ($group) {
                $gross = $group->sum('gross_score');
                $coursePar = $group->first()->m_course_num_par;
                $toPar = $gross - $coursePar;
                $handicap = $group->first()->handicap ?? 0;
                $net_score = $group->first()->netscore ?? 0;

                return [
                    'name' => $group->first()->t_user_name,
                    'gross' => $gross,
                    'to_par' => $toPar,
                    'handicap' => $handicap,
                    'net_score' => $net_score
                ];
            });

            $result = $result->sortBy('gross')->values()->all();

            return $result;
        } catch(\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function store_proof_payment($t_event_id)
    {
        DB::beginTransaction();
        try {
            $memberEvent = $this->memberEvent->where('t_event_id', $t_event_id)->where('t_user_id',  auth()->user()->id)->first();

            if (empty($memberEvent) || !isset($memberEvent)) {
                return $this->api->error();
            }

            $message = ($memberEvent->approve == "PAID") ? "Payment approved" : "Payment cancelled";
            if ($memberEvent->approve != "WAITING_FOR_PAYMENT") {
                return $this->api->error($message);
            }

            $folder = "dgolf/proof-payment";
            $column = "image";
            $upload = $this->helper->uploads($folder, $memberEvent, $column);

            $memberEvent->update(["approve" => "PAID"]);
            if ($upload) {
            } else {
                return $this->api->error("Gagal mengupload bukti pembayaran");
            }
            DB::commit();
            return $this->api->success($memberEvent, 'Succes Upload Bukti Pembayaran');
        } catch(\Throwable $e) {
            DB::rollback();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function update_proof_payment($t_event_id)
    {
        DB::beginTransaction();
        try {
            $memberEvent = $this->memberEvent->where('t_event_id', $t_event_id)->where('t_user_id',  auth()->user()->id)->first();

            if (empty($memberEvent) || !isset($memberEvent)) {
                return $this->api->error();
            }

            $message = ($memberEvent->approve == "PAID") ? "Payment approved" : "Payment cancelled";
            if ($memberEvent->approve != "WAITING_FOR_PAYMENT") {
                return $this->api->error($message);
            }

            $folder = "dgolf/proof-payment";
            $column = "image";
            $upload = $this->helper->uploads($folder, $memberEvent, $column);

            $memberEvent->update(["approve" => "PAID"]);
            if ($upload) {
            } else {
                return $this->api->error("Gagal mengupdate bukti pembayaran");
            }
            DB::commit();
            return $this->api->success($memberEvent, 'Succes Update Bukti Pembayaran');
        } catch(\Throwable $e) {
            DB::rollback();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function template()
    {
        DB::beginTransaction();
        try {

            DB::commit();
            return $this->api->success(null, 'Template Retrieved');
        } catch(\Throwable $e) {
            DB::rollback();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function check_community(Request $request)
    {
        try {
            $user = $request->user();
            // $komunitas = $user->userCommonity()->get();

            // $show = [
            //     'joined' => $komunitas->isNotEmpty(),
            //     'komunitas' => $komunitas
            // ];

            $semuaKomunitas = Community::get();
            $komunitasUser = $user->membersCommonity()->pluck('t_community_id')->toArray();

            // Tandai mana yang sudah diikuti
            $data = $semuaKomunitas->map(function ($komunitas) use ($komunitasUser) {
                $komunitas->is_joined = in_array($komunitas->id, $komunitasUser);
                return $komunitas;
            });

            $show = [
                'komunitas' => $data
            ];
    
            return $this->api->list($show, $this->users, "Check Komunitas");
        } catch (\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }
}
