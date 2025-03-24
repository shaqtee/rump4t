<?php

namespace Modules\SocialMedia\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ApiResponse;
use App\Services\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\SocialMedia\App\Models\DiscussionGroup;
use Modules\SocialMedia\App\Models\DiscussionGroupMember;
use Modules\SocialMedia\App\Models\DiscussionMessage;
use Modules\SocialMedia\App\Services\Interfaces\SocialMediaInterface;

class FormGroupDiscussionController extends Controller
{
    public function __construct(
        protected DiscussionGroup $discussionGroup,
        protected DiscussionGroupMember $discussionGroupMember,
        protected DiscussionMessage $discussionMessage,
        protected SocialMediaInterface $interface,
        protected ApiResponse $api,
        protected Helper $helper,
    )
    {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            // $page = $request->size ?? 10;
            $index =  $this->discussionGroupMember->where('t_user_id', auth()->user()->id)->with(['group'])->filter($request)->orderByDesc('id')->get();

            return $this->api->list($index, $this->discussionGroup);
        } catch(\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            }
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
                'name' => 'required|string',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:png,jpg,jpeg',
                't_members_id' => 'required|array'
            ]);

            $datas['t_user_id'] = auth()->user()->id;
            $store = $this->interface->storeDiscussionGroup($this->discussionGroup, $datas);

            $folder = "dgolf/social-media/discussion-group/profile-picture";
            $column = "image";

            $this->helper->uploads($folder, $store, $column);

            DB::commit();
            return $this->api->success($store, "Group Has Been Created");
        } catch(\Throwable $e) {
            DB::rollBack();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            }
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        try{
            $show = $this->interface->showDiscussionGroup($this->discussionGroup, $id);
            return $this->api->success($show);
        } catch(\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            }
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
                'name' => 'required|string',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:png,jpg,jpeg',
            ]);

            $update = $this->interface->updateDiscussionGroup($this->discussionGroup, $datas, $id);

            $folder = "dgolf/social-media/discussion-group/profile-picture";
            $column = "image";

            $this->helper->uploads($folder, $update, $column);

            DB::commit();
            return $this->api->success($update, "Group Has Been Updated");
        } catch(\Throwable $e) {
            DB::rollBack();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try{
            $delete = $this->interface->destroyDiscussionGroup($this->discussionGroup, $this->discussionGroupMember, $this->discussionMessage, $id);

            DB::commit();
            return $this->api->success($delete, "Group Has Been Deleted");
        } catch(\Throwable $e) {
            DB::rollBack();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            }
        }
    }

    public function getUsers(Request $request)
    {
        try{
            $show = $this->interface->showUser($request);

            return $this->api->success($show);
        } catch(\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            }
        }
    }

    public function storeMembers(Request $request, $id)
    {
        DB::beginTransaction();
        try{
            $datas = $request->validate([
                't_members_id' => 'required|array'
            ]);

            $discussionGroup = $this->discussionGroup->find($id);
            if (!$discussionGroup) return $this->api->error();

            $store = $this->interface->storeDiscussionGroupMember($discussionGroup, $datas);

            DB::commit();
            return $this->api->success($store, "Members Has Been Invited");
        } catch(\Throwable $e) {
            DB::rollBack();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            }
        }
    }

    public function storeMessage(Request $request, $id)
    {
        DB::beginTransaction();
        try{
            $datas = $request->validate([
                'message' => 'required|string'
            ]);

            $discussionGroup = $this->discussionGroup->find($id);
            if (!$discussionGroup) return $this->api->error();

            $store = $this->interface->storeDiscussionMessage($discussionGroup, $this->discussionMessage, $datas);

            DB::commit();
            return $this->api->success($store);
        } catch(\Throwable $e) {
            DB::rollBack();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            }
        }
    }

    public function deleteMessage($id)
    {
        DB::beginTransaction();
        try{
            $delete = $this->interface->destroyMessage($this->discussionMessage, $id);

            DB::commit();
            return $this->api->success($delete);
        } catch(\Throwable $e) {
            DB::rollBack();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            }
        }
    }
}
