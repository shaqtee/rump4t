<?php

namespace Modules\SocialMedia\App\Http\Controllers;

use App\Exceptions\Handler;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ApiResponse;
use App\Services\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Community\App\Models\EventCommonity;
use Modules\SocialMedia\App\Models\Post;
use Modules\SocialMedia\App\Models\DetailPost;
use Modules\SocialMedia\App\Models\ReportPost;
use Modules\SocialMedia\App\Models\UserBlock;
use Modules\SocialMedia\App\Services\Interfaces\SocialMediaInterface;

class SocialMediaController extends Controller
{
    protected $model;
    protected $interface;
    protected $helper;
    protected $handler;
    protected $api;
    protected $users;
    protected $event;
    protected $detailpost;
    protected $reportpost;
    protected $userblock;


    public function __construct(Post $model, SocialMediaInterface $interface, Helper $helper, Handler $handler, ApiResponse $api, User $users, EventCommonity $event, DetailPost $detailpost, ReportPost $reportpost, UserBlock $userblock)
    {
        $this->model = $model;
        $this->interface = $interface;
        $this->helper = $helper;
        $this->handler = $handler;
        $this->api = $api;
        $this->users = $users;
        $this->event = $event;
        $this->detailpost = $detailpost;
        $this->reportpost = $reportpost;
        $this->userblock = $userblock;
    }

    /**
     * Display a listing of the resource.
    */
    public function index(Request $request)
    {
        try {
            $page = $request->size ?? 10;
            $authUserId = auth()->user()->id;
            $blockedUsers = $this->userblock->where('t_user_blocker_id', $authUserId)->get()->pluck('t_user_blocked_id')->unique()->values();
            $reportPost = $this->reportpost->where('t_user_id', $authUserId)->get()->pluck('t_post_id')->unique()->values();
            $index = $this->model->with(['user:id,name,image'])->withCount('comment')->whereNotIn('id', $reportPost)->whereNotIn('id_user', $blockedUsers)->filter($request)->orderByDesc('id')->paginate($page);

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
        try{
            $request['id_user'] = Auth::id();
            $store = $this->interface->store($this->model, $request->all());

            $folder = "dgolf/social-media";
            $column = "url_cover_image";

            $this->helper->uploads($folder, $store, $column);

            DB::commit();
            return $this->api->success($store, "Data has been added");
        } catch(\Throwable $e) {
            DB::rollBack();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function report_post(Request $request)
    {
        DB::beginTransaction();
        try {
            $datas = $request->validate([
                't_post_id' => 'required|integer'
            ]);

            $checkPost = $this->model->find($request->t_post_id);
            if (!$checkPost) return $this->api->error('Data Post Not Found');
            $checkReport = $this->reportpost->where('t_post_id', $request->t_post_id)->where('t_user_id', auth()->id())->first();
            if ($checkReport) return $this->api->error('you have already reported this post');

            $datas['t_user_id'] = auth()->id();
            $store = $this->reportpost->create($datas);

            $user = $this->users->find($checkPost->id_user);
            $user->update(['active' => 0]);

            DB::commit();
            return $this->api->success($store,  "Report Successfully");
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
    public function destroy_post($id)
    {
        DB::beginTransaction();
        try {
            $delete = $this->model->findOrFail($id);
            $delete->delete();

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

    public function showdetail($id)
    {
        try{
            $show = $this->detailpost->with(['user:id,name,image'])->where('id_post', $id)->get();

            if ($show->isEmpty()) {
                return $this->api->error("Comments not found for this post");
            }

            return  $this->api->success($show,  "Success to get data");
        } catch(\Throwable $e) {
            DB::rollBack();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function storedetail(Request $request, $id)
    {
        DB::beginTransaction();
        try{

            $request['id_post'] = $id;

            $storedetail = $this->detailpost->create([
                'id_post' => $id,
                'id_user' => $request->user_id,
                'komentar' => $request->komentar
            ]);

            DB::commit();
            return  $this->api->success($storedetail,  "Success to send comment");
        } catch(\Throwable $e) {
            DB::rollBack();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function blockuser(Request $request)
    {
        DB::beginTransaction();
        try{
            $request->validate([
                't_user_blocked_id' => 'required',
            ]);

            $blockerId = auth()->user()->id;
            $blockedId = $request->t_user_blocked_id;

            if ($blockerId == $blockedId) {
                return $this->api->error('You cannot block yourself', 400);
            }

            if ($this->userblock->where('t_user_blocker_id', $blockerId)->where('t_user_blocked_id', $blockedId)->exists()) {
                return $this->api->error('User already blocked', 400);
            }

            $this->userblock->create([
                't_user_blocker_id' => $blockerId,
                't_user_blocked_id' => $blockedId,
            ]);

            DB::commit();
            return $this->api->success('User blocked successfully', 200);
        } catch(\Throwable $e) {
            DB::rollBack();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function unblockuser(Request $request)
    {
        DB::beginTransaction();
        try{
            $request->validate([
                't_user_blocked_id' => 'required|exists:users,id',
            ]);

            $blockerId = auth()->id();
            $blockedId = $request->t_user_blocked_id;

            $userblock = $this->userblock->where('t_user_blocker_id', $blockerId)->where('t_user_blocked_id', $blockedId)->first();

            if (!$userblock) {
                return $this->api->error('User not found in block list', 404);
            }

            $userblock->delete();

            DB::commit();
            return $this->api->success('User unblocked successfully', 200);
        } catch(\Throwable $e) {
            DB::rollBack();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }
}
