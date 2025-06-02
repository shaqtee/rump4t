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
use Modules\SocialMedia\App\Models\Post;
use Illuminate\Http\RedirectResponse;

class GroupsPostingController extends Controller
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

    public function index(Request $request)
    {
        try{
            $page = $request->size ?? 10;
            $data = [
                'content' => 'Admin/Groups/Posting/index',
                'groups' => $this->model->filter($request)->orderBy('id', 'asc')->paginate($page),
                'columns' => $this->model->columns(),
            ];

            return view('Admin.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function index_posts(Request $request, $groups_id)
    {
        try{
            $raw_user_id = User::select('id')->where('region', auth()->user()->region)->get()->toArray();
            $arr_user_id = [];
            foreach($raw_user_id as $aui){
                $arr_user_id[] = $aui['id'];
            }
            
            if(auth()->user()->t_group_id == 3){
                $posts = Post::whereIn('id_user', $arr_user_id)
                    ->where('t_small_groups_id', $groups_id)
                    ->orderBy("created_at" , "desc")->withTrashed() ->paginate(6);
            }else{
                $posts = Post::orderBy("created_at" , "desc")
                    ->where('t_small_groups_id', $groups_id)
                    ->withTrashed() ->paginate(6);
            }
            
            $data = [
                'content' => 'Admin/Groups/Posting/index_posts',
                'posts' => $posts,
                'groups_id' => $groups_id,
            ];

            return view('Admin.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }

    }

    public function create($groups_id)
    {
        $data = [
            'content' => 'Admin/Groups/Posting/create',
            'groups_id' => $groups_id,
        ];

        return view('Admin.Layouts.wrapper', $data);
        // return view('socialmedia.moderations::create');
    }

    public function store(Request $request, $groups_id): RedirectResponse
    {
        // dd($groups_id);
        try {
            $img = null;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $path = 'socialmedia/' . $filename;
                \Storage::disk('s3')->put($path, file_get_contents($file));
                $img = \Storage::disk('s3')->url($path);
            }
            DB::beginTransaction();
            $post = Post::create([
                'title' => $request->title,
                'desc' => $request->content,
                'id_user' => auth()->user()->id,
                'url_cover_image' => $img,
                't_small_groups_id' => $groups_id,
            ]) ;
            $post->save();
            DB::commit();
            return redirect()->route('groups.posting.posts', ['groups_id' => $groups_id])->with('success', 'Post created successfully.');

        }catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to upload image: ' . $e->getMessage());
        }
    }
}
