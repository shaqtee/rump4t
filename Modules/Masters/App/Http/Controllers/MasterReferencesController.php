<?php

namespace Modules\Masters\App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use Modules\Masters\App\Models\MasterReferences;
use Modules\Masters\App\Services\Interfaces\MastersInterface;

class MasterReferencesController extends Controller
{
    protected $model;
    protected $api;
    protected $helper;
    protected $interface;

    public function __construct(MasterReferences $model, ApiResponse $api, Helper $helper, MastersInterface $interface)
    {
        $this->model = $model;
        $this->api  = $api;
        $this->helper = $helper;
        $this->interface = $interface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $parameter = null)
    {
        try {
            $page = $request->size ?? 10;
            $index = $this->model->filter($request, $parameter)->where('parameter', $parameter)->with(['parent'])->orderBy('id', 'desc')->paginate($page);

            return $this->api->lists($index, $this->model, $parameter);
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
            $parameter = $request->parameter;
            $code = $request->code;

            $data = $this->model->where('parameter', $parameter)->where('code', $code)->first();

            if ($data) {
                return $this->api->error_code("code sudah ada sebelumnya");
            }

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
    public function show($parameter, $id)
    {
        try {
            $show = $this->model->where('parameter', $parameter)->with(['parent'])->find($id);
        
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
    public function update(Request $request, $parameter, $id)
    {
        DB::beginTransaction();
        try {
            $parameter = $request->parameter;
            $code = $request->code;

            $data = $this->model->where('parameter', $parameter)->where('code', $code)->first();

            if ($data) {
                if ($data->id != $id) {
                    return $this->api->error_code("code sudah ada sebelumnya");
                }
            }
            
            $update = $this->interface->update($this->model, $request->all(), $id);
        
            DB::commit();
            return $this->api->update($update);
        } catch(\Throwable $e) {
            DB::rollBack();
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
