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
use Modules\Events\App\Models\EventCommonity;

class EventsController extends Controller
{
    protected $helper;
    protected $api;
    protected $users;
    protected $event;
    protected $userblock;

    public function __construct(Helper $helper, ApiResponse $api, User $users, EventCommonity $event, UserBlock $userblock)
    {
        $this->helper = $helper;
        $this->api = $api;
        $this->users = $users;
        $this->event = $event;
        $this->userblock = $userblock;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = auth()->id();

        $event = $this->event->with('membersEvent')->get();

        $event->transform(function ($event) use ($userId) {
            $event->is_join = $event->joinBy($userId);
            return $event;
        });

        return $this->api->success($event, "success");
    }

    public function index_by_region($region_id)
    {
        $event_by_region = $this->event->with('membersEvent')
            ->where('region', (int) $region_id)->get();
        return $this->api->success($event_by_region, "success");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('events::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->all();
    
            $event = $this->event->create([
                't_community_id' => $data['t_community_id'] ?? NULL,
                'title' => $data['title'] ?? NULL,
                'image' => $data['image'] ?? NULL,
                'description' => $data['description'] ?? NULL,
                'location' => $data['location'] ?? NULL,
                'latitude' => $data['latitude'] ?? NULL,
                'longitude' => $data['longitude'] ?? NULL,
                'play_date_start' => $data['play_date_start'] ?? NULL,
                'play_date_end' => $data['play_date_end'] ?? NULL,
                'close_registration' => $data['close_registration'] ?? NULL,
                'price' => $data['price'] ?? NULL,
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
                'region' => $data['region'] ?? NULL,
            ]);
            DB::commit();

            return $this->api->success($event,'success');
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
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('events::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $event = $this->event->where('id', $id)->first();

            $event->t_community_id = $data['t_community_id'] ?? $event->t_community_id;
            $event->image = $data['image'] ?? $event->image;
            $event->title = $data['title'] ?? $event->title;
            $event->location = $data['location'] ?? $event->location;
            $event->latitude = $data['latitude'] ?? $event->latitude;
            $event->longitude = $data['latitude'] ?? $event->longitude;
            $event->play_date_start = $data['play_date_start'] ?? $event->play_date_start;
            $event->play_date_end = $data['play_date_end'] ?? $event->play_date_end;
            $event->close_registration = $data['close_registration'] ?? $event->close_registration;
            $event->price = $data['price'] ?? $event->price;
            $event->description = $data['description'] ?? $event->description;
            $event->updated_by = Auth::user()->id;
            $event->save();

            DB::commit();
            return $this->api->success($this->rump4t_event_view($id), 'event successfully updated');
        } catch (\Throwable $e) {
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
            $event = $this->event->find($id);
            $rsp = $event->delete();
            DB::commit();
            return $this->api->success($rsp, "event deleted");
        } catch (\Throwable $e) {
            DB::rollBack();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    private function rump4t_event_view($id)
    {
        $event = $this->event->find($id);
        $custom_view = [
            "id" => $event->id,
            "t_community_id" => $event->t_community_id,
            "image" => $event->image,
            "title" => $event->title,
            "location" => $event->location,
            "latitude" => $event->latitude,
            "longitude" => $event->longitude,
            "play_date_start" => $event->play_date_start,
            "play_date_end" => $event->play_date_end,
            "close_registration" => $event->close_registration,
            "price" => $event->price,
            "description" => $event->description,
            "created_at" => $event->created_at,
            "created_by" => $event->created_by,
            "updated_at" => $event->updated_at,
            "updated_by" => $event->updated_by,
        ];
        return $custom_view;
    }
}
