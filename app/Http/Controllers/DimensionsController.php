<?php

namespace App\Http\Controllers;

use App\Models\Dimensions;
use Illuminate\Http\Request;
use App\Services\ApiResponse;
use Modules\Masters\App\Services\Interfaces\MastersInterface;

class DimensionsController extends Controller
{
    protected $api;
    protected $model;

    public function __construct()
    {
        $this->api = new ApiResponse();
        $this->model = new Dimensions();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $index = $this->model->get();

            return $this->api->success($index);
        } catch(\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $datas = $request->validate([
                'height' => 'required',
                'width' => 'required',
            ]);

            $check = $this->model->where(function ($q) use($datas){
                $q->where('height', $datas['height'])->where('width', $datas['width']);
            })->first();

            if (!empty($check)) {
                return $this->api->success($check, "Data has been added");
            }

            $store = $this->model->create($datas);
            return $this->api->success($store, "Data has been added");
        } catch(\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Dimensions $dimensions)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dimensions $dimensions)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dimensions $dimensions)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dimensions $dimensions)
    {
        //
    }
}
