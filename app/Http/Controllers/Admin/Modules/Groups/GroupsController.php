<?php

namespace App\Http\Controllers\Admin\Modules\Groups;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Exceptions\Handler;
use App\Services\WebRedirect;
use App\Services\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Group;
use App\Models\SmallGroupUser;
use Modules\Masters\App\Models\MasterCity;
use Modules\Groups\App\Models\Group as SmallGroup;
use Illuminate\Validation\ValidationException;
use Modules\Community\App\Models\MembersCommonity;

class GroupsController extends Controller
{
    protected $model;
    protected $helper;
    protected $city;
    protected $web;
    protected $handler;
    protected $users;
    protected $members;
    protected $groups;
    protected $smallGroupUser;

    public function __construct(SmallGroup $model,SmallGroupUser $smallGroupUser, Helper $helper, MasterCity $city, WebRedirect $web, Handler $handler, User $users, MembersCommonity $members, Group $groups)
    {
        $this->model = $model;
        $this->helper = $helper;
        $this->city = $city;
        $this->web = $web;
        $this->handler = $handler;
        $this->users = $users;
        $this->members = $members;
        $this->groups = $groups;
        $this->smallGroupUser = $smallGroupUser;
    }

    public function index(Request $request){
        // dd('groups');
        try{
            $page = $request->size ?? 10;
            $data = [
                'content' => 'Admin/Groups/index',
                'title' => 'Data Groups',
                'community' => $this->model->filter($request)->orderBy('id', 'asc')->paginate($page),
            ];

            return view('Admin.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function create()
    {
        try{
            $data = [
                'content' => 'Admin/Groups/add',
                'city' => $this->city->get(),
            ];
            return view('Admin.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

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

            $folder = "rump4t/sm-group/sm-group-profile";
            $column = "image";

            $model = $this->model->create($datas);

            $this->helper->uploads($folder, $model, $column);

            DB::commit();
            return $this->web->store('groups.semua');
        } catch (\Throwable $e) {
            DB::rollBack();
            if($e instanceof ValidationException){
                return $this->web->error_validation($e);
            }
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function edit($id)
    {
        try{
            $data = [
                'content' => 'Admin/Groups/edit',
                'community' => $this->model->with(['city'])->findOrfail($id),
                'city' => $this->city->get(),
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
                'title' => 'required',
                'description' => 'required',
                'location' => 'required',
                // 't_city_id' => 'required',
                'image' => 'nullable',
            ]);

            $folder = "rump4t/sm-group/sm-group-profile";
            $column = "image";

            $model = $this->model->findOrfail($id);

            $model->update($datas);

            $this->helper->uploads($folder, $model, $column);

            DB::commit();
            return $this->web->update('groups.semua');
        } catch (\Throwable $e) {
            DB::rollBack();
            if($e instanceof ValidationException){
                return $this->web->error_validation($e);
            }
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try{
            $this->model->findOrfail($id)->delete();
            DB::commit();

            return $this->web->destroy('groups.semua');
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function user_member(Request $request, $groups_id)
    {
        $members = $this->users->whereHas('small_groups', function($q) use($groups_id) {
                    $q->where('t_small_groups_user.t_small_groups_id', $groups_id);
                });
                
        $ids = [];
        foreach($members->get()->toArray() as $m){
            $ids[] = $m['id'];
        }
        
        try{
            $page = $request->size ?? 10;
            $data = [
                'content' => 'Admin/Groups/member',
                'title' => 'Data Group Members',
                'group' => $members->with('small_groups')->filter($request)->orderByDesc('id')->paginate($page)->appends($request->all()),
                'users' => $this->users->whereNotIn('id', $ids)->where('active', 1)->get(),
                'columns' => $this->users->columnsWeb(),
                'groups_id' => $groups_id,
            ];

            return view('Admin.Layouts.wrapper', $data);

        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function add_member(Request $request, $groups_id)
    {
        DB::beginTransaction();
        try {
            $datas = $request->validate([
                    't_small_groups_id' => 'required',
                    'user_id' => 'required',
                    'is_admin' => 'nullable',
                ]);
                
            $this->smallGroupUser->create($datas);
            DB::commit();

            return $this->web->successReturn('groups.member', 'groups_id', $groups_id);
        } catch (\Throwable $e) {
            report($e);
            DB::rollBack();
            if($e instanceof ValidationException){
                return $this->web->error_validation($e);
            }
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function left_member($id)
    {
        DB::beginTransaction();
        try {
            $pivot = $this->smallGroupUser->find($id);
            $pivot->delete();
            DB::commit();
            
            return $this->web->destroyBack('Berhasil melepaskan member dari group.');
        } catch (\Throwable $e) {
            report($e);
            DB::rollBack();

            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function change_status_admin(Request $request)
    {
        $pivot = $this->smallGroupUser->find($request->id);
        $pivot->is_admin = !$pivot->is_admin;
        $pivot->save();

        return response()->json([
            'status' => 'success',
            'data' => $pivot,
        ]);
    }
}
