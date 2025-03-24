<?php

namespace Modules\Masters\App\Services\Repositories;

use Modules\Masters\App\Services\Interfaces\MastersInterface;

class MastersRepository implements MastersInterface
{
    /**
     * Store Masters
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
     * Update Masters
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
     * Destroy Masters
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
     * Destroy Masters
     *
     * @param array $data
     * @return void
     */
    public function destroyBulk($models, array $data = null)
    {
        $deleteData = $models->whereIn('id', $data)->delete();
        return $deleteData;
    }
}