<?php

namespace Modules\SocialMedia\App\Services\Interfaces;

interface SocialMediaInterface
{
    /**
     * Store
     *
     * @param $request->all()
     *
     * @return void
     * */
    public function store($models, array $data);

    /**
     * Store Discussion Group
     *
     * @param array $request->all()
     * @return void
     */
    public function storeDiscussionGroup($models, array $data);

    /**
     * Store Discussion Group Member
     *
     * @param array $request->all()
     * @return void
     */
    public function storeDiscussionGroupMember($discussionGroup, array $data);

    /**
     * Store Discussion Message
     *
     * @param array $request->all()
     * @return void
     */
    public function storeDiscussionMessage($discussionGroup, $discussionMessage, array $data);

    /**
     * Show Discussion Group
     *
     * @param array $id
     * @return void
     */
    public function showDiscussionGroup($models, $id);

    /**
     * Show User Invite
     *
     * @param array $id
     * @return void
     */
    public function showUser($datas);

    /**
     * Update
     *
     * @param $request->all()
     *
     * @return void
     * */
    public function update($models, array $data, $id);

    /**
     * Update Discussion Group
     *
     * @param array $request->all()
     * @return void
     */
    public function updateDiscussionGroup($models, array $data, $id);

    /**
     * Destroy
     *
     * @param $request->all()
     *
     * @return void
     * */
    public function destroy($models, $id);

    /**
     * Destroy
     *
     * @param $request->all()
     *
     * @return void
     * */
    public function destroyBulk($models, array $data);

    /**
     * Destroy Discussion Group
     *
     * @param array $data
     * @return void
     */
    public function destroyDiscussionGroup($discussionGroup, $discussionGroupMember, $discussionMessage, $id);

    /**
     * Destroy Message
     *
     * @param array $data
     * @return void
     */
    public function destroyMessage($models, $id);

}
