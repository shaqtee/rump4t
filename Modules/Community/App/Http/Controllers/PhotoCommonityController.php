<?php

namespace Modules\Community\App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiResponse;
use Illuminate\Http\Response;
use App\Services\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\Community\App\Models\PhotoCommonity;
use Modules\Community\App\Services\Interfaces\CommunityInterface;

class PhotoCommonityController extends Controller
{
    protected $api;
    protected $helper;
    protected $interface;
    protected $model;

    public function __construct(ApiResponse $api, Helper $helper, CommunityInterface $interface, PhotoCommonity $model)
    {
        $this->api = $api;
        $this->helper = $helper;
        $this->interface = $interface;
        $this->model = $model;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $page = $request->size ?? 10;
            $index = $this->model->filter($request)->orderByDesc('id')->paginate($page);

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
        try {
            $store = $this->interface->store($this->model, $request->all());
            //save photo profile commonity
            $folder = "dgolf/community/album-photo";
            $column = "image";
            $this->helper->uploads($folder, $store, $column);

            DB::commit();
            return $this->api->success($store, "Data has been added");
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
     * Show the specified resource.
     */
    public function show($id)
    {
        try {
            $show = $this->model->find($id);

            if (!$show) {
                return $this->api->error("Data Not Found");
            }

            return  $this->api->success($show,  "Success to get data");
        } catch(\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $update = $this->interface->update($this->model, $request->all(), $id);
            //update photo profile commonity
            $folder = "dgolf/community/album-photo";
            $column = "image";
            $this->helper->uploads($folder, $update, $column);

            DB::commit();
            return  $this->api->success($update,  "Update Successfully");
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
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $delete = $this->interface->destroy($this->model, $id);
            
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

    public function bulk_destroy(Request $request)
    {
        DB::beginTransaction();
        try {
            $bulkDestroy = $this->interface->destroyBulk($this->model, $request->all());

            DB::commit();
            return  $this->api->delete($bulkDestroy);
        } catch(\Throwable $e) {
            DB::rollback();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function template()
    {
        DB::beginTransaction();
        try {

            DB::commit();
            return $this->api->success(null, 'Template Retrieved');
        } catch(\Throwable $e) {
            DB::rollback();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }
}
