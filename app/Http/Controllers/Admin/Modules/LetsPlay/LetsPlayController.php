<?php

namespace App\Http\Controllers\Admin\Modules\LetsPlay;

use Carbon\Carbon;
use App\Models\User;
use App\Exceptions\Handler;
use Illuminate\Http\Request;
use App\Services\WebRedirect;
use App\Services\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Modules\MyGames\App\Models\LetsPlay;
use Illuminate\Validation\ValidationException;
use Modules\MyGames\App\Models\MemberLetsPlay;
use Modules\Performace\App\Models\ScoreHandicap;

class LetsPlayController extends Controller
{
    protected $model;
    protected $web;
    protected $helper;
    protected $handler;
    protected $members;
    protected $scoreHandicap;
    protected $user;

    public function __construct(LetsPlay $model, WebRedirect $web, Helper $helper, Handler $handler, MemberLetsPlay $members, ScoreHandicap $scoreHandicap, User $user)
    {
        $this->model = $model;
        $this->web   = $web;
        $this->helper= $helper;
        $this->handler= $handler;
        $this->members= $members;
        $this->scoreHandicap= $scoreHandicap;
        $this->user = $user;
    }

    public function index(Request $request){
        try{
            $page = $request->size ?? 10;
            $data = [
                'content' => 'Admin/LetsPlay/index',
                'title' => 'List Lets Play',
                'letsPlay' => $this->model->with(['organized', 'golfCourse', 'teeBox', 'roundType'])->filterWeb($request)->orderBy('id', 'asc')->paginate($page)->appends($request->all()),
                'columns' => $this->model->columnsWeb(),
            ];

            return view('Admin.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try{
            $datas = $request->validate([
                'active' => 'required',
            ]);

            $model = $this->model->findOrfail($id);

            $model->update($datas);

            DB::commit();
            return $this->web->update('letsplay.semua');
        } catch (\Throwable $e) {
            DB::rollBack();
            if($e instanceof ValidationException){
                return $this->web->error_validation($e);
            }
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function member(Request $request, $id){
        try{
            $page = $request->size ?? 10;

            $select = "users.name as name, users.image as image, users.email as email, users.phone as phone";

            $members = DB::table('t_member_lets_play')->select(DB::raw($select))
                            ->leftJoin('users', 't_member_lets_play.t_user_id', '=', 'users.id')
                            ->leftJoin('t_lets_play', 't_member_lets_play.t_lets_play_id', '=', 't_lets_play.id')
                            ->where('t_lets_play.id', $id)
                            ->where('t_member_lets_play.approve', 'ACCEPT')
                            ->orderByDesc('t_member_lets_play.id')->paginate($page);

            $data = [
                'content' => 'Admin/LetsPlay/member',
                'title' => 'List Member Lets Play',
                // 'members' => $this->model->with([
                //         'memberLetsPlay' => function($q) {
                //             $q->where('t_member_lets_play.approve', 'ACCEPT');
                //         }
                //     ])->filter($request)->orderBy('id', 'asc')->findOrfail($id),
                'members' => $members,
                'lets_play_id' => $id,
                'users' => $this->user->where('flag_done_profile', 1)->get(),
            ];

            return view('Admin.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function create_input_score($id){
        try{
            $data = [
                'content' => 'Admin/LetsPlay/inputScore',
                'title' => 'Input Score',
                'letsPlay' => $this->model->with(['organized', 'memberLetsPlay', 'golfCourse', 'teeBox', 'roundType'])->findOrfail($id),
                'score' => null,
            ];

            return view('Admin.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function store_input_score(Request $request)
    {
        DB::beginTransaction();
        try {
            $datas = $request->validate([
                't_lets_play_id' => 'required|integer',
                't_user_id' => 'required|integer',
                'gross_score' => 'required|integer',
                'image' => 'image',
            ]);

            $dbScoreHandicap = $this->scoreHandicap;

            $checkScoreUser = $dbScoreHandicap->with(['user:id,name', 'letsPlay:id,title'])->where('t_user_id', $datas['t_user_id'])->where('t_lets_play_id', $datas['t_lets_play_id'])->first();

            $folder = "dgolf/score-handicap";
            $column = "image_score";

            if ($checkScoreUser) {
                $datas['image_score'] = $datas['image'];
                unset($datas['t_lets_play_id'], $datas['t_user_id'], $datas['image']);
                $checkScoreUser->update($datas);
                $this->helper->uploads($folder, $checkScoreUser, $column);
                DB::commit();
                return $this->web->updateBack();
            }

            $letsPlay = $this->model->with(['golfCourse', 'teeBox', 'roundType'])->findOrfail($datas['t_lets_play_id']);

            $data = [
                't_lets_play_id' => $datas['t_lets_play_id'],
                't_user_id' => $datas['t_user_id'],
                'gross_score' => $datas['gross_score'],
                't_course_id' => $letsPlay->golfCourse->id,
                't_course_name' => $letsPlay->golfCourse->name,
                'date' => now(),
                't_tee_id' => $letsPlay->teeBox->id,
                't_tee_name' => $letsPlay->teeBox->tee_type,
                'course_rating' => $letsPlay->teeBox->course_rating,
                'slope_rating' => $letsPlay->teeBox->slope_rating,
                'm_round_type_id' => $letsPlay->roundType->id,
                'm_round_type_name' => $letsPlay->roundType->value1,
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
            $letsplay = $this->model->findOrFail($request->t_lets_play_id);
            $user = $this->user->where('flag_done_profile', 1)->findOrFail($request->t_user_id);

            if (!isset($user) || !isset($letsplay)) {
                return $this->error("Data tidak di temukan!");
            }
            if (Carbon::now()->greaterThan($letsplay->play_date)) {
                return $this->web->error("Permainan sedang berjalan atau sudah berakhir.");
            }

            $this->members->create([
                "t_user_id" => $user->id,
                "t_lets_play_id" => $letsplay->id,
                "approve" => "ACCEPT",
            ]);
            DB::commit();
            return $this->web->successReturn("letsplay.member", "id", $letsplay->id);
        } catch (\Throwable $e) {
            DB::rollBack();
            if($e instanceof ValidationException){
                return $this->web->error_validation($e);
            }
            return $this->handler->handleExceptionWeb($e);
        }
    }
}