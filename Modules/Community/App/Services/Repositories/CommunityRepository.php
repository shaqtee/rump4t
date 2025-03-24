<?php

namespace Modules\Community\App\Services\Repositories;

use Modules\Community\App\Services\Interfaces\CommunityInterface;

class CommunityRepository implements CommunityInterface
{
    /**
     * Store Community
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
     * Update Community
     *
     * @param array $data
     * @return void
     */
    public function update($models, array $data = null, $id)
    {
        $updateData = $models->findOrFail($id);
        $updateData->update($data);

        return $updateData;
    }

    /**
     * Destroy Community
     *
     * @param array $data
     * @return void
     */
    public function destroy($models, $id)
    {
        $deleteData = $models->findOrFail($id);
        $deleteData->delete();
        return $deleteData;
    }

    /**
     * Destroy Community
     *
     * @param array $data
     * @return void
     */
    public function destroyBulk($models, array $data = null)
    {
        $deleteData = $models->whereIn('id', $data)->delete();
        return $deleteData;
    }

    public function store_sponsor($models, $modelSocialMedia, array $data)
    {
        $addData = $models->create($data);

        $data['social_media']['table_name'] = "t_sponsor";
        $data['social_media']['table_id'] = $addData->id;

        $modelSocialMedia->create($data['social_media']);

        return $addData;
    }

    public function update_sponsor($models, $modelSocialMedia, array $data = null, $id)
    {
        $updateData = $models->findOrFail($id);
        $updateDataSocialMedia = $modelSocialMedia->where('table_name', 't_sponsor')->where("table_id", $id)->first();

        $updateData->update($data);
        $updateDataSocialMedia->update($data['social_media']);

        return $updateData;
    }

    public function destroy_sponsor($models, $modelSocialMedia, $id)
    {
        $deleteData = $models->findOrFail($id);
        $updateDataSocialMedia = $modelSocialMedia->where('table_name', 't_sponsor')->where("table_id", $id)->first();

        $deleteData->delete();
        $updateDataSocialMedia->delete();
        
        return $deleteData;
    }

    public function destroyBulk_sponsor($models, $modelSocialMedia, array $data = null)
    {
        $deleteData = $models->whereIn('id', $data)->delete();
        $updateDataSocialMedia = $modelSocialMedia->where('table_name', 't_sponsor')->whereIn("table_id", $data)->delete();

        return $deleteData;
    }
}