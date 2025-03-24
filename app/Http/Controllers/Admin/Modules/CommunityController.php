<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Exceptions\Handler;
use Illuminate\Http\Request;
use App\Services\ApiResponse;
use App\Services\WebRedirect;
use App\Services\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\User;
use Modules\Masters\App\Models\MasterCity;
use Modules\Community\App\Models\Community;
use Illuminate\Validation\ValidationException;
use Modules\Community\App\Models\MembersCommonity;

class CommunityController extends Controller
{
    protected $model;
    protected $helper;
    protected $city;
    protected $web;
    protected $handler;
    protected $users;
    protected $members;
    protected $groups;

    public function __construct(Community $model, Helper $helper, MasterCity $city, WebRedirect $web, Handler $handler, User $users, MembersCommonity $members, Group $groups)
    {
        $this->model = $model;
        $this->helper = $helper;
        $this->city = $city;
        $this->web = $web;
        $this->handler = $handler;
        $this->users = $users;
        $this->members = $members;
        $this->groups = $groups;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request){
        try{
            $page = $request->size ?? 10;
            $data = [
                'content' => 'Admin/Community/index',
                'community' => $this->model->filter($request)->orderBy('id', 'asc')->paginate($page),
            ];

            return view('Admin.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    /**
     * Create a newly created resource in storage.
     */
    public function create()
    {
        try{
            $data = [
                'content' => 'Admin/Community/add',
                'city' => $this->city->get(),
            ];
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
            $datas = $request->validate([
                'title' => 'required',
                'description' => 'required',
                'location' => 'required',
                // 't_city_id' => 'required',
                'image' => 'required',
            ]);

            $folder = "dgolf/community/community-profile";
            $column = "image";

            $model = $this->model->create($datas);

            $this->helper->uploads($folder, $model, $column);

            DB::commit();
            return $this->web->store('community.semua');
        } catch (\Throwable $e) {
            DB::rollBack();
            if($e instanceof ValidationException){
                return $this->web->error_validation($e);
            }
            return $this->handler->handleExceptionWeb($e);
        }
    }

    /**
     * Show the specified resource.
     */
    public function edit($id)
    {
        try{
            $data = [
                'content' => 'Admin/Community/edit',
                'community' => $this->model->with(['city'])->findOrfail($id),
                'city' => $this->city->get(),
            ];
    
            return view('Admin.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try{
            $datas = $request->validate([
                'title' => 'required',
                'description' => 'required',
                'location' => 'required',
                // 't_city_id' => 'required',
                'image' => 'nullable',
            ]);

            $folder = "dgolf/community/community-profile";
            $column = "image";

            $model = $this->model->findOrfail($id);

            $model->update($datas);

            $this->helper->uploads($folder, $model, $column);

            DB::commit();
            return $this->web->update('community.semua');
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
    public function destroy($id)
    {
        DB::beginTransaction();
        try{
            $this->model->findOrfail($id)->delete();

            DB::commit();
            return $this->web->destroy('community.semua');
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function user_index(Request $request, $community_id)
    {
        try{
            $page = $request->size ?? 10;
            $data = [
                'content' => 'Admin/Community/add-manage-people',
                'title' => 'Data User For people Manage',
                'users' => $this->users->where('flag_done_profile', 1)->where('active', 1)->where('flag_community', 'JOINED')->where('t_community_id', $community_id)->with(['community:id,title', 'group:id,name', 'city:id,name'])->whereRelation('membersCommonity', 'flag_manage', 1)->filter($request)->orderByDesc('id')->paginate($page)->appends($request->all()), //->whereRelation('membersCommonity', 'flag_manage', null)
                'groups' => $this->groups->where('active', 1)->get(),
                'community' => $this->model->orderBy('id', 'asc')->get(),
                'columns' => $this->users->columnsWeb(),
                'community_id' => $community_id,
            ];

            return view('Admin.Layouts.wrapper', $data);

        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function user_member(Request $request, $community_id)
    {
        try{
            $page = $request->size ?? 10;
            $data = [
                'content' => 'Admin/Community/member',
                'title' => 'Data User Members',
                'users' => $this->users->where('flag_done_profile', 1)->where('active', 1)->where('flag_community', 'JOINED')->where('t_community_id', $community_id)->with(['community:id,title', 'group:id,name', 'city:id,name'])->filter($request)->orderByDesc('id')->paginate($page)->appends($request->all()), //->whereRelation('membersCommonity', 'flag_manage', null)
                'groups' => $this->groups->where('active', 1)->get(),
                'community' => $this->model->orderBy('id', 'asc')->get(),
                'columns' => $this->users->columnsWeb(),
                'community_id' => $community_id,
            ];

            return view('Admin.Layouts.wrapper', $data);

        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function add_member(Request $request)
    {
        DB::beginTransaction();
        try{
            $user = $this->users->findOrfail($request->t_user_id);
            $members = $this->members->where('t_user_id', $user->id)->where('t_community_id', $user->t_community_id)->first();
            
            $datas['flag_manage'] = '1';
            $datasUser['t_group_id'] = $request->t_group_id;
            $datasUser['is_admin'] = 2;
            
            if($request->type == 'remove') {
                $datas['flag_manage'] = null;
                $datasUser['t_group_id'] = null;
                $datasUser['is_admin'] = 0;
            }

            $user->update($datasUser);
            $members->update($datas);

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

    public function leaderboard($community_id)
    {
        try{
            $select = "users.id as t_user_id, users.name as t_user_name, 
                   m_golf_course.id as m_course_id, m_golf_course.name as m_course_name, m_golf_course.number_par as m_course_num_par,
                   t_score_handicap.gross_score as gross_score";
                //    m_configurations.id as m_tee_id, m_configurations.value1 as m_tee_name,

            $data = DB::table('t_score_handicap')->select(DB::raw($select))
                        ->leftJoin('users', 't_score_handicap.t_user_id', '=', 'users.id')
                        ->leftJoin('m_golf_course', 't_score_handicap.t_course_id', '=', 'm_golf_course.id')
                        // ->leftJoin('m_configurations', 't_score_handicap.t_tee_id', '=', 'm_configurations.id')
                        // ->where('m_configurations.parameter', 'm_tee')
                        ->where('t_score_handicap.t_community_id', '=', $community_id)
                        ->where('users.t_community_id', '=', $community_id)
                        ->whereNotNull('users.t_community_id')
                        ->orderBy('users.id', 'ASC')
                        ->get();

            $collection = collect($data);
            $groupedData = $collection->groupBy('t_user_name');
            $result = $groupedData->map(function ($group) {
                $gross = $group->sum('gross_score');
                $coursePar = $group->first()->m_course_num_par;
                $toPar = $gross - $coursePar;
                $totalMatch = $group->count();

                return [
                    'name' => $group->first()->t_user_name,
                    'gross' => $gross,
                    'to_par' => $toPar,
                    'total_match' => $totalMatch,
                ];
            });

            $result = $result->sortBy(function ($item) {
                return [
                    'total_match' => -$item['total_match'],
                    'gross' => $item['gross'],
                ];
            })->values()->all();

            $data = [
                'content' => 'Admin/Community/leaderboard',
                'title' => 'Leaderboard',
                'leaderboard' => $result,
            ];

            return view('Admin.Layouts.wrapper', $data);

        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }
}
