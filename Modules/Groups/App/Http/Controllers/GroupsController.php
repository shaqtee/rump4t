<?php

namespace Modules\Groups\App\Http\Controllers;

use DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Group;
use App\Models\SmallGroupUser;
use App\Services\Helpers\Helper;
use App\Exceptions\Handler;
use Modules\Masters\App\Models\MasterCity;
use Modules\Groups\App\Models\Group as SmallGroup;
use Modules\Community\App\Models\MembersCommonity;

class GroupsController extends Controller
{
    public function __construct(
        protected SmallGroup $smallGroup,
        protected SmallGroupUser $smallGroupUser, 
        protected Helper $helper, 
        protected MasterCity $city,
        protected Handler $handler, 
        protected User $users, 
        protected MembersCommonity $members, 
        protected Group $groups
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = SmallGroup::query();
            $page = $request->input('size', 10);
            
            if ($request->has('size')) {
                $groups = $query
                    ->with('region')
                    ->filter($request)
                    ->paginate($page)
                    ->appends($request->all());
            }else{
                $groups = $query->with('region')->filter($request)->get();
            }

            return response()->json([
                'status' => 'success',
                'data' => $groups
            ]);
        } catch (\Exception $e) {
            report($e);

            return response()->json([
                'status' => 'failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('groups::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $datas = $request->validate([
                'title' => 'required',
                'description' => 'required',
                'location' => 'required',
                'image' => 'required',
            ]);
            
            $folder = "rump4t/sm-group/sm-group-profile";
            $column = "image";
    
            $model = $this->smallGroup->create($datas);
    
            $this->helper->uploads($folder, $model, $column);
    
            DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => $model
            ]);

        } catch (\Exception $e) {
            report($e);
            DB::rollBack();

            return response()->json([
                'status' => 'failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('groups::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('groups::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try{
            $datas = $request->validate([
                'title' => 'sometimes|required',
                'description' => 'sometimes|required',
                'location' => 'sometimes|required',
                't_city_id' => 'sometimes|required',
                'image' => 'sometimes|required',
            ]);

            $folder = "rump4t/sm-group/sm-group-profile";
            $column = "image";
            
            $model = $this->smallGroup->findOrfail($id);
            $model->update($datas);


            $this->helper->uploads($folder, $model, $column);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => $model
            ]);

        } catch (\Exception $e) {
            report($e);
            DB::rollBack();

            return response()->json([
                'status' => 'failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $group = SmallGroup::findOrFail($id);
            $group->delete();
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Group berhasil dihapus.'
            ]);

        } catch (\Exception $e) {
            report($e);
            DB::rollBack();

            return response()->json([
                'status' => 'failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function index_member(Request $request, $group_id)
    {
        $members = $this->users->whereHas('small_groups', function($q) use($group_id) {
                    $q->where('t_small_groups_user.t_small_groups_id', $group_id);
                });
                
        $ids = [];
        foreach($members->get()->toArray() as $m){
            $ids[] = $m['id'];
        }

        try {
            $page = $request->input('size', 10);
            $data = [
                'group' => $members->with('small_groups')->filter($request)->orderByDesc('id')->paginate($page)->appends($request->all()),
                'users' => $this->users->whereNotIn('id', $ids)->where('active', 1)->get(),
                'groups_id' => $group_id,
            ];

            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            report($e);

            return response()->json([
                'status' => 'failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function add_member(Request $request)
    {
        DB::beginTransaction();
        try {
            $datas = $request->validate([
                    't_small_groups_id' => 'required',
                    'user_id' => 'required',
                    'is_admin' => 'nullable',
                ]);
                
            $data = $this->smallGroupUser->create($datas);
            DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);

        } catch (\Exception $e) {
            report($e);
            DB::rollback();

            return response()->json([
                'status' => 'failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function left_member(Request $request)
    {
        DB::beginTransaction();
        try {
            $left_member = $this->smallGroupUser->find($request->t_small_group_user_id);
            $left_member->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Member berhasil dikeluarkan dari Grup.'
            ]);
            
        } catch (\Exception $e) {
            report($e);
            DB::rollback();

            return response()->json([
                'status' => 'failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function be_admin(Request $request)
    {
        $pivot = $this->smallGroupUser->find($request->t_small_group_user_id);
        $pivot->is_admin = !$pivot->is_admin;
        $pivot->save();

        return response()->json([
            'status' => 'success',
            'data' => $pivot,
        ]);
    }
}
