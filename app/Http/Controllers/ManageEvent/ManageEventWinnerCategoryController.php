<?php

namespace App\Http\Controllers\ManageEvent;

use App\Models\User;
use App\Exceptions\Handler;
use Illuminate\Http\Request;
use App\Services\WebRedirect;
use App\Services\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Modules\Community\App\Models\MemberEvent;
use Illuminate\Validation\ValidationException;
use Modules\Community\App\Models\EventCommonity;
use Modules\Community\App\Models\WinnerCategoryEvent;

class ManageEventWinnerCategoryController extends Controller
{
    protected $model;
    protected $helper;
    protected $handler;
    protected $web;
    protected $event;
    protected $users;
    protected $memberEvent;

    public function __construct(WinnerCategoryEvent $model, Helper $helper, Handler $handler, WebRedirect $web, EventCommonity $event, User $users, MemberEvent $memberEvent)
    {
        $this->model = $model;
        $this->helper = $helper;
        $this->handler = $handler;
        $this->web = $web;
        $this->event = $event;
        $this->users = $users;
        $this->memberEvent = $memberEvent;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $id)
    {
        try{
            $data = [
                'content' => 'ManageEvent/Event/Winner-Category/index',
                'title' => 'Data Winner Category',
                'eventWinner' =>  $this->event->with([
                                            'winnerCategory' => function($q) {
                                                $q->with(['usersWinner', 'masterWinnerCategory'])->orderBy('id','asc');
                                            },
                                        ])->orderByDesc('id')->findOrfail($id),
            ];

            return view('ManageEvent.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try{
            $data = [
                'content' => 'ManageEvent/Event/Winner-Category/addEdit',
                'title' => 'Create Winner Category',
                'event' => $this->event->get(),
            ];
            return view('ManageEvent.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            $datas = $request->validate([
                't_event_id' => 'required',
                'code' => 'required',
                'name' => 'required',
                'description' => 'nullable',
            ]);
            $datas['code'] = strtoupper($datas['code']);
            
            $models = $this->model;

            $isExists = $models->where('code', $datas['code'])->exists();
            if($isExists){
                return $this->web->error("Code already exists.");
            }
            
            $models->create($datas);

            DB::commit();
            return $this->web->store('event.manage-event.winners.semua');
        } catch (\Throwable $e) {
            DB::rollBack();
            if($e instanceof ValidationException){
                return $this->web->error_validation($e);
            }
            return $this->handler->handleExceptionWeb($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try{
            $userEvent = $this->memberEvent->select('t_user_id')->where('t_event_id', $id)->where('approve', 'PAID')->get()->toArray();
 
            $data = [
                'content' => 'ManageEvent/Event/Winner-Category/addEdit',
                'title' => 'Edit Winner Category',
                'eventWinner' =>  $this->event->with([
                                                    'winnerCategory' => function($q) {
                                                        $q->with(['usersWinner', 'masterWinnerCategory'])->orderBy('id','asc');
                                                    },
                                                ])->orderByDesc('id')->findOrfail($id),
                'userEvent' => $this->users->select('id', 'name')->whereIn('id', $userEvent)->where('active', 1)->get(),
            ];

            return view('ManageEvent.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function _edit(string $id)
    {
        try{
            $data = [
                'content' => 'ManageEvent/Event/Winner-Category/_addEdit',
                'title' => 'Edit Winner Category',
                'winner_category' => $this->model->with(['event'])->findOrfail($id),
                'event' => $this->event->get(),
            ];
            return view('ManageEvent.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();
        try{
            $datas = $request->validate([
                't_user_id' => 'nullable',
                'name' => 'nullable',
                'checkbox' => 'nullable',
            ]);

            if(isset($datas['checkbox']) && !empty($datas['checkbox']) && $datas['checkbox'] == true) {
                $datas['t_user_id'] = null;
            } else {
                $datas['name'] = null;
            }

            $model = $this->model->findOrfail($id);
            $model->update($datas);

            $event = $this->event->with([
                'membersEvent' => function($q) {
                    $q->where('t_member_event.approve', 'PAID')->whereNotNull('users.fcm_token');
                }
            ])->find($model['t_event_id']);

            $FcmToken = collect();
            foreach($event->membersEvent as $getFcmToken) {
                $map = $getFcmToken->fcm_token;

                $FcmToken->push($map);
            }

            $this->helper->pushNotification2($FcmToken->toArray(), "Informasi Event", "Winner Category Telah Ditambahkan Pada $event->title", null, 'EVENT', $id, 'winner_category', $id);
            DB::commit();
            return $this->web->updateBack();
        } catch (\Throwable $e) {
            DB::rollBack();
            if($e instanceof ValidationException){
                return $this->web->error_validation($e);
            }
            return $this->handler->handleExceptionWeb($e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function _update(Request $request, string $id)
    {
        DB::beginTransaction();
        try{
            $datas = $request->validate([
                't_event_id' => 'required',
                'code' => 'required',
                'name' => 'required',
                'description' => 'nullable',
            ]);
            
            $models = $this->model;

            $isExists = $models->whereNot('id', $id)->where('code', $datas['code'])->exists();
            if($isExists){
                return $this->web->error("Code already exists.");
            }

            $model = $models->findOrfail($id);
            $model->update($datas);

            DB::commit();
            return $this->web->update('event.manage-event.winners.semua');
        } catch (\Throwable $e) {
            DB::rollBack();
            if($e instanceof ValidationException){
                return $this->web->error_validation($e);
            }
            return $this->handler->handleExceptionWeb($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try{
            $this->model->findOrfail($id)->delete();
            DB::commit();
            return $this->web->destroy('event.manage-event.winners.semua');
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->handler->handleExceptionWeb($e);
        }
    }
}
