<?php

namespace App\Http\Controllers\ManagePeople\Modules\Events;

use App\Exceptions\Handler;
use Illuminate\Http\Request;
use App\Services\WebRedirect;
use App\Services\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Modules\Community\App\Models\Community;
use Illuminate\Validation\ValidationException;
use Modules\Community\App\Models\AlbumCommonity;
use Modules\Community\App\Models\EventCommonity;
use Modules\Community\App\Models\PhotoCommonity;

class ManageAlbumEventController extends Controller
{
    protected $model;
    protected $helper;
    protected $handler;
    protected $web;
    protected $event;
    protected $modelPhoto;

    public function __construct(AlbumCommonity $model, Helper $helper, Handler $handler, WebRedirect $web, EventCommonity $event, PhotoCommonity $modelPhoto)
    {
        $this->model = $model;
        $this->helper = $helper;
        $this->handler = $handler;
        $this->web = $web;
        $this->event = $event;
        $this->modelPhoto = $modelPhoto;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $event_id)
    {
        try{
            $page = $request->size ?? 10;
            $data = [
                'content' => 'ManagePeople/Event/Album/index',
                'title' => 'Data Album',
                'albums' =>  $this->model->with(['albumEvent'])->whereNotNull('t_event_id')->where('t_event_id', $event_id)->orderByDesc('id')->paginate($page),
                'event' => $this->event->findOrfail($event_id),
            ];
            return view('ManagePeople.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($event_id)
    {
        try{
            $data = [
                'content' => 'ManagePeople/Event/Album/addEdit',
                'title' => 'Create Album',
                'albums' => null,
                'event' => $this->event->findOrfail($event_id),
            ];
            return view('ManagePeople.Layouts.wrapper', $data);
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
                'name' => 'required',
                'cover' => 'required|image|file|max:2048|mimes:jpeg,png,jpg',
                'description' => 'required',
            ]);

            $folder = "dgolf/community/album";
            $column = "cover";

            $model = $this->model->create($datas);

            $this->helper->uploads($folder, $model, $column);

            $event = $this->event->where('id', $datas['t_event_id'])->first();

            $com = Community::with([
                'membersCommonity' => function($q) {
                    $q->whereNotNull('users.fcm_token');
                }
            ])->find($event['t_community_id']);

            $FcmToken = collect();
            foreach($com->membersCommonity as $getFcmToken) {
                $map = $getFcmToken->fcm_token;

                $FcmToken->push($map);
            }

            $this->helper->pushNotification2($FcmToken->toArray(), "Informasi Event", "Album $model->name Telah Ditambahkan", $model->cover, 'EVENT', $datas['t_event_id'], 'album', $model->id);
            DB::commit();
            return $this->web->successReturn('event.album.photo.semua', 'album_id', $model->id, 'Berhasil Menambah Data, Silahkan Tambahkan Photo');
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
    public function edit(string $id)
    {
        try{
            $data = [
                'content' => 'ManagePeople/Event/Album/addEdit',
                'title' => 'Edit Album',
                'albums' => $this->model->with(['albumEvent'])->findOrfail($id),
                // 'event' => $this->event->get(),
            ];
            return view('ManagePeople.Layouts.wrapper', $data);
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
                't_event_id' => 'required',
                'name' => 'required',
                'cover' => 'required|image|file|max:2048|mimes:jpeg,png,jpg',
                'description' => 'required',
            ]);

            $folder = "dgolf/community/album";
            $column = "cover";

            $model = $this->model->findOrfail($id);

            $model->update($datas);

            $this->helper->uploads($folder, $model, $column);

            DB::commit();
            return $this->web->update('event.album.semua');
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
            return $this->web->destroy('event.album.semua');
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->handler->handleExceptionWeb($e);
        }
    }


    /**
     * Display a listing of the resource.
     */
    public function photo_index(Request $request, $id)
    {
        try{
            $page = $request->size ?? 10;
            $data = [
                'content' => 'ManagePeople/Event/Album/Photo/index',
                'title' => 'Data Photo',
                'photos' =>  $this->modelPhoto->with(['photoEvent'])->where('t_album_id', $id)->orderByDesc('id')->paginate($page),
                'albums' => $this->model->find($id),
            ];
            return view('ManagePeople.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function photo_create($album_id)
    {
        try{
            $data = [
                'content' => 'ManagePeople/Event/Album/Photo/addEdit',
                'title' => 'Create Photo Album',
                'albums' => $this->model->findOrfail($album_id),
                'photos' => null,
            ];
            return view('ManagePeople.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function photo_store(Request $request)
    {
        DB::beginTransaction();
        try{
            $datas = $request->validate([
                't_album_id' => 'required',
                'name' => 'required',
                'image' => 'required|image|file|max:2048|mimes:jpeg,png,jpg',
                'active' => 'required',
            ]);

            $folder = "dgolf/community/album-photo";
            $column = "image";

            $model = $this->modelPhoto->create($datas);

            $this->helper->uploads($folder, $model, $column);

            DB::commit();
            return $this->web->successBack();
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
    public function photo_show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function photo_edit(string $id)
    {
        try{
            $data = [
                'content' => 'ManagePeople/Event/Album/Photo/addEdit',
                'title' => 'Edit Photo',
                'photos' => $this->modelPhoto->with(['photoEvent'])->findOrfail($id),
            ];
            return view('ManagePeople.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function photo_update(Request $request, string $id)
    {
        DB::beginTransaction();
        try{
            $datas = $request->validate([
                't_album_id' => 'required',
                'name' => 'required',
                'image' => 'nullable|image|file|max:2048|mimes:jpeg,png,jpg',
                'active' => 'required',
            ]);

            $folder = "dgolf/community/album-photo";
            $column = "image";

            $model = $this->modelPhoto->findOrfail($id);

            $model->update($datas);

            $this->helper->uploads($folder, $model, $column);

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
     * Remove the specified resource from storage.
     */
    public function photo_destroy(string $id)
    {
        DB::beginTransaction();
        try{
            $this->modelPhoto->findOrfail($id)->delete();
            DB::commit();
            return $this->web->destroy('event.album.photo.semua');
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->handler->handleExceptionWeb($e);
        }
    }
}
