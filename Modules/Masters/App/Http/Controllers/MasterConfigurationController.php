<?php

namespace Modules\Masters\App\Http\Controllers;

use MasterInterface;
use Illuminate\Http\Request;
use App\Services\ApiResponse;
use App\Services\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Modules\Masters\App\Models\MasterConfiguration;
use Modules\Masters\App\Services\Interfaces\MastersInterface;

class MasterConfigurationController extends Controller
{
    protected $model;
    protected $api;
    protected $helper;
    protected $interface;

    public function __construct(MasterConfiguration $model, ApiResponse $api, Helper $helper, MastersInterface $interface)
    {
        $this->model = $model;
        $this->api  = $api;
        $this->helper = $helper;
        $this->interface = $interface;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $page = $request->size ?? 10;
            $index = $this->model->filter($request)->orderBy('id', 'desc')->paginate($page);

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

            DB::commit();
            return $this->api->store($store);
        } catch (\Throwable$e) {
            DB::rollBack();
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
        
            return $this->api->success($show);
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
        try {
            $update = $this->interface->update($this->model, $request->all(), $id);
        
            return $this->api->update($update);
        } catch(\Throwable $e) {
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
        try {
            $destroy = $this->interface->destroy($this->model, $id);
        
            return $this->api->delete($destroy);
        } catch(\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    /**
     * Delete the selected resource from storage.
     */
    public function bulk_destroy(Request $request)
    {
        DB::beginTransaction();
        try {
            $delete = $this->interface->destroyBulk($this->model, $request->all());
            
            DB::commit();

            return $this->api->delete($delete);
        } catch (\Throwable$e) {
            DB::rollBack();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }
}
