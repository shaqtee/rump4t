<?php

namespace Modules\SocialMedia\App\Services\Repositories;

use App\Models\User;
use App\Services\ApiResponse;
use App\Services\Helpers\Helper;
use Carbon\Carbon;
use Modules\Community\App\Models\MembersCommonity;
use Modules\Masters\App\Models\MasterConfiguration;
use Modules\SocialMedia\App\Models\DiscussionGroupMember;
use Modules\SocialMedia\App\Services\Interfaces\SocialMediaInterface;

class SocialMediaRepository implements SocialMediaInterface
{
    /**
     * Store
     *
     * @param array $data
     * @return void
     */
    public function store($models, array $data = null)
    {
        $addData = $models->create($data);
        return $addData;
    }

    /**
     * Store Discussion Group
     *
     * @param array $data
     * @return void
     */
    public function storeDiscussionGroup($models, array $data = null)
    {
        $addData = $models->create($data);
        return $this->storeDiscussionGroupMember($addData, $data);
    }

    /**
     * Store Discussion Group Member
     *
     * @param array $data
     * @return void
     */
    public function storeDiscussionGroupMember($discussionGroup, array $data = null)
    {
        if ($discussionGroup->t_user_id <> auth()->user()->id) return ApiResponse::error_repositories("Only admins can invite users", 400);

        $users = User::select('id')->whereIn('id', $data['t_members_id'])->get();
        $role = MasterConfiguration::where('parameter', 'm_roles')->where('value1', 'members')->first();
        $roleAdmin = MasterConfiguration::where('parameter', 'm_roles')->where('value1', 'admin')->first();
        $checkAdmin = DiscussionGroupMember::where('t_group_id', $discussionGroup->id)->where('t_user_id', auth()->user()->id)->first();
        if (!$checkAdmin) {
            $datas = collect([[
                't_group_id' => $discussionGroup->id,
                't_user_id' => auth()->user()->id,
                'm_roles_id' => $roleAdmin->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]]);
        } else {
            $datas = collect();
        }
        $users->each(function($item, $key) use($discussionGroup, $datas, $role){
            $datas->push([
                't_group_id' => $discussionGroup->id,
                't_user_id' => $item->id,
                'm_roles_id' => $role->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        DiscussionGroupMember::insert($datas->toArray());

        return $discussionGroup;
    }

    /**
     * Store Discussion Member
     *
     * @param array $data
     * @return void
     */
    public function storeDiscussionMessage($discussionGroup, $discussionMessage, array $data = null)
    {
        $data['t_group_id'] = $discussionGroup->id;
        $data['t_user_id'] = auth()->user()->id;
        $discussionMessage->create($data);
        return true;
    }

    /**
     * Show Discussion Group
     *
     * @param array $id
     * @return void
     */
    public function showDiscussionGroup($models, $id)
    {
        $loggedInUserId = auth()->user()->id;

        $show = $models->with(['creator:id,name', 'members:id,name', 'messages.user:id,name,image'])->find($id);
        if (!$show) return ApiResponse::error_repositories();

        $groupedMessages = [
            // 'me' => [],
            // 'strangger' => [],
        ];

        $show->messages->each(function ($message) use (&$groupedMessages, $loggedInUserId) {
            $key = $message->t_user_id == $loggedInUserId ? 'me' : 'strangger';
            $date = Carbon::parse($message->created_at)->format('Y-m-d');

            $groupedMessages[$date][$key][] = [
                'id' => $message->id,
                't_group_id' => $message->t_group_id,
                't_user_id' => $message->t_user_id,
                'message' => $message->message,
                'created_at' => $message->created_at,
                'updated_at' => $message->updated_at,
                'user' => $message->user,
            ];
        });

        // foreach ($groupedMessages as $key => $messages) {
        //     ksort($groupedMessages[$key]);
        // }

        unset($show->messages);
        $show->messages = $groupedMessages;
        return $show;
    }

    /**
     * Show User Invite
     *
     * @param array $id
     * @return void
     */
    public function showUser($datas)
    {
        $memberIds = MembersCommonity::where('t_user_id', auth()->user()->id)->pluck('t_community_id')->unique()->values();
        return User::select('id', 'name')->whereIn('t_community_id', $memberIds)->whereNot('id', auth()->user()->id)->filter($datas)->get();
    }

    /**
     * Update
     *
     * @param array $data
     * @return void
     */
    public function update($models, array $data = null, $id)
    {
        $updateData = $models->find($id);
        if (!$updateData) return ApiResponse::error_repositories();
        $updateData->update($data);

        return $updateData;
    }

    /**
     * Update Discussion Group
     *
     * @param array $data
     * @return void
     */
    public function updateDiscussionGroup($models, array $data = null, $id)
    {
        $updateData = $models->find($id);
        if ($updateData->t_user_id <> auth()->user()->id) return ApiResponse::error_repositories("Only admins can update", 400);
        $updateData->update($data);
        return true;
    }

    /**
     * Destroy
     *
     * @param array $data
     * @return void
     */
    public function destroy($models, $id)
    {
        $deleteData = $models->find($id);
        if (!$deleteData) return ApiResponse::error_repositories();
        $deleteData->delete();
        return $deleteData;
    }

    /**
     * Destroy bulk
     *
     * @param array $data
     * @return void
     */
    public function destroyBulk($models, array $data = null)
    {
        $deleteData = $models->whereIn('id', $data)->delete();
        return $deleteData;
    }

    /**
     * Destroy Discussion Group
     *
     * @param array $data
     * @return void
     */
    public function destroyDiscussionGroup($discussionGroup, $discussionGroupMember, $discussionMessage, $id)
    {
        $deleteDiscussionGroup = $discussionGroup->find($id);

        if (!$deleteDiscussionGroup) return ApiResponse::error_repositories();

        $deleteDiscussionGroupMember = $discussionGroupMember->where('t_group_id', $id);
        $deleteDiscussionMessage = $discussionMessage->where('t_group_id', $id);

        $folder = "dgolf/social-media/discussion-group/profile-picture";
        $column = "image";
        Helper::deleteUploads($folder, $deleteDiscussionGroup, $column);

        $deleteDiscussionGroup->delete();
        $deleteDiscussionGroupMember->delete();
        $deleteDiscussionMessage->delete();

        return true;
    }

    /**
     * Destroy Message
     *
     * @param array $data
     * @return void
     */
    public function destroyMessage($models, $id)
    {
        $deleteData = $models->where('t_user_id', auth()->user()->id)->find($id);
        if (!$deleteData) return ApiResponse::error_repositories();
        $deleteData->delete();
        return $deleteData;
    }
}
