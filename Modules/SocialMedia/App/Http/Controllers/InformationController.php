<?php

namespace Modules\SocialMedia\App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiResponse;
use App\Services\Helpers\Helper;
use App\Http\Controllers\Controller;
use Modules\Community\App\Models\MemberEvent;
use Modules\SocialMedia\App\Models\Information;

class InformationController extends Controller
{
    protected $model;
    protected $memberEvent;
    protected $helper;
    protected $api;

    public function __construct(Information $model, MemberEvent $memberEvent, Helper $helper, ApiResponse $api)
    {
        $this->model = $model;
        $this->memberEvent = $memberEvent;
        $this->helper = $helper;
        $this->api = $api;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $page = $request->size ?? 10;
            $memberEvent = $this->memberEvent->where('t_user_id', auth()->user()->id)->get()->pluck('t_event_id')->values();
            $index = $this->model->whereIn('t_event_id', $memberEvent)->filter($request)->orderByDesc('id')->paginate($page);

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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('socialmedia::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        try{
            $show = $this->model->find($id);

            if (!$show) {
                return $this->api->error();
            }

            return  $this->api->success($show);
        } catch(\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('socialmedia::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
