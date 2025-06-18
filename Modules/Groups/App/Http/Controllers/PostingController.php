<?php

namespace Modules\Groups\App\Http\Controllers;

use App\Models\User;
use App\Models\Group;
use App\Exceptions\Handler;
use Illuminate\Http\Request;

use Illuminate\Http\Response;
use App\Models\SmallGroupUser;
use App\Services\Helpers\Helper;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\SocialMedia\App\Models\Post;
use Modules\Masters\App\Models\MasterCity;
use Modules\Community\App\Models\MembersCommonity;
use Modules\Groups\App\Models\Group as SmallGroup;

class PostingController extends Controller
{
    public function __construct(
        protected SmallGroup $model,
        protected SmallGroupUser $smallGroupUser, 
        protected Helper $helper, 
        protected MasterCity $city, 
        protected Handler $handler, 
        protected User $users, 
        protected MembersCommonity $members, 
        protected Group $groups
    ){}

    /**
     * Display a listing of the resource.
     */
    public function index_posts($groups_id)
    {
        try{
            $raw_user_id = User::select('id')->where('region', auth()->user()->region)->get()->toArray();
            $arr_user_id = [];
            foreach($raw_user_id as $aui){
                $arr_user_id[] = $aui['id'];
            }
            
            if(auth()->user()->t_group_id == 3){
                $posts = Post::whereIn('id_user', $arr_user_id)
                    ->with('postingGroup')
                    ->where('t_small_groups_id', $groups_id)
                    ->orderBy("created_at" , "desc")->withTrashed() ->paginate(6);
            }else{
                $posts = Post::orderBy("created_at" , "desc")
                    ->with('postingGroup')
                    ->where('t_small_groups_id', $groups_id)
                    ->withTrashed() ->paginate(6);
            }
            
            $data = [
                'group' => $this->model->where('id', $groups_id)->first(),
                'posts' => $posts,
                'groups_id' => $groups_id,
            ];
            
            return response()->json([
                "status" => "success",
                "data" => $data
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
    public function store(Request $request, $group_id): JsonResponse
    {
        $validator = $request->validate([
            'title' => 'required',
            'desc' => 'required',
            'image' => 'nullable|image',
        ]);

        try {
            \DB::beginTransaction();

            $img = null;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $path = 'socialmedia/' . $filename;
                \Storage::disk('s3')->put($path, file_get_contents($file));
                $img = \Storage::disk('s3')->url($path);
            }

            $post = Post::create([
                'title' => $validator['title'],
                'desc' => $validator['desc'],
                'id_user' => auth()->id(),
                'url_cover_image' => $img,
                't_small_groups_id' => $group_id,
            ]);

            \DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => $post,
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            \DB::rollBack();

            return response()->json([
                'status' => 'failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($group_id, $id): JsonResponse
    {
        $post = Post::where('t_small_groups_id', $group_id)->find($id);

        if (!$post) {
            return response()->json([
                'status' => 'failed',
                'error' => 'Post not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $post,
        ]);
    }

    public function update(Request $request, $group_id, $id): JsonResponse
    {
        $validator = $request->validate([
            'title' => 'sometimes|required',
            'desc' => 'sometimes|required',
            'image' => 'nullable|image',
        ]);

        $post = Post::where('t_small_groups_id', $group_id)->find($id);
        if (!$post) {
            return response()->json([
                'status' => 'failed',
                'error' => 'Post not found',
            ], 404);
        }

        try {
            \DB::beginTransaction();

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $path = 'socialmedia/' . $filename;
                \Storage::disk('s3')->put($path, file_get_contents($file));
                $validator['url_cover_image'] = \Storage::disk('s3')->url($path);
            }

            $post->update($validator);

            \DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => $post,
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();

            return response()->json([
                'status' => 'failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($group_id, $id): JsonResponse
    {
        $post = Post::where('t_small_groups_id', $group_id)->find($id);

        if (!$post) {
            return response()->json([
                'status' => 'failed',
                'error' => 'Post not found',
            ], 404);
        }

        $post->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }

}
