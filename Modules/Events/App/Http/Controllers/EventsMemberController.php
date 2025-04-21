<?php

namespace Modules\Events\App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\ApiResponse;
use Illuminate\Http\Response;
use App\Services\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Modules\SocialMedia\App\Models\UserBlock;
use Modules\Community\App\Models\EventCommonity;
use Modules\Community\App\Models\MemberEvent;

class EventsMemberController extends Controller
{
    protected $helper;
    protected $api;
    protected $users;
    protected $event;
    protected $userblock;
    protected $member_event;

    public function __construct(Helper $helper, ApiResponse $api, User $users, EventCommonity $event, MemberEvent $member_event, UserBlock $userblock)
    {
        $this->helper = $helper;
        $this->api = $api;
        $this->users = $users;
        $this->event = $event;
        $this->userblock = $userblock;
        $this->member_event = $member_event;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('events::index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->all();
            DB::commit();
            
            $member_event = $this->member_event->create([
                "t_user_id" => $data["t_user_id"],
                "t_event_id" => $data["t_event_id"],
                "approve" => $data["approve"], // PAID or WAITING FOR PAYMENT
                "voucher" => $data["voucher"],
                "payment_date" => $data["payment_date"],
                "flag_cancel" => $data["flag_cancel"], // 0 or 1
                "image" => $data["image"],
                "nominal_pembayaran" => $data["nominal_pembayaran"], 
                "data_input" => isset($data["custom_fields"]) ? json_encode($data["custom_fields"]) : null
            ]);

            return $this->api->success($member_event,'success');
        } catch (\Throwable $e) {
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
        return view('events::show');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $member_event = $this->member_event->find($id);
            $rsp = $member_event->delete();
            DB::commit();
            return $this->api->success($rsp, "deleted");
        } catch (\Throwable $e) {
            DB::rollBack();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }
}
