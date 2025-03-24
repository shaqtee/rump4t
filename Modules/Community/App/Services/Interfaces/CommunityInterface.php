<?php

namespace Modules\Community\App\Services\Interfaces;

interface CommunityInterface
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
     * Update
     *
     * @param $request->all()
     *
     * @return void
     * */
    public function update($models, array $data, $id);

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
     * Store
     *
     * @param $request->all()
     *
     * @return void
     * */
    public function store_sponsor($models, $modelSocialMedia, array $data);

    /**
     * Update
     *
     * @param $request->all()
     *
     * @return void
     * */
    public function update_sponsor($models, $modelSocialMedia, array $data, $id);

    /**
     * Destroy
     *
     * @param $request->all()
     *
     * @return void
     * */
    public function destroy_sponsor($models, $modelSocialMedia, $id);

    /**
     * Destroy
     *
     * @param $request->all()
     *
     * @return void
     * */
    public function destroyBulk_sponsor($models, $modelSocialMedia, array $data);
}