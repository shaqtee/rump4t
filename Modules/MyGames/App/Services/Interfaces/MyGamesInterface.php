<?php

namespace Modules\MyGames\App\Services\Interfaces;

interface MyGamesInterface
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
}