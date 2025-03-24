<?php

namespace Modules\ScoreHandicap\App\Services\Repositories;

use Modules\ScoreHandicap\App\Services\Interfaces\ScoreHandicapInterface;

class ScoreHandicapRepository implements ScoreHandicapInterface
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
}