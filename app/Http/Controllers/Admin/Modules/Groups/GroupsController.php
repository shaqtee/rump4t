<?php

namespace App\Http\Controllers\Admin\Modules\Groups;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Exceptions\Handler;
use App\Services\ApiResponse;
use App\Services\WebRedirect;
use App\Services\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use App\Models\Group;
use App\Models\User;
use Modules\Masters\App\Models\MasterCity;
use Modules\Community\App\Models\Community;
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

    public function __construct(SmallGroup $model, Helper $helper, MasterCity $city, WebRedirect $web, Handler $handler, User $users, MembersCommonity $members, Group $groups)
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

    public function index(Request $request){
        // dd('groups');
        try{
            $page = $request->size ?? 10;
            $data = [
                'content' => 'Admin/Groups/index',
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
}
