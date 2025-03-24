<?php

namespace App\Http\Controllers\ManagePeople\Modules;

use App\Exceptions\Handler;
use Illuminate\Http\Request;
use App\Services\WebRedirect;
use App\Services\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Modules\Community\App\Models\Community;
use Illuminate\Validation\ValidationException;
use Modules\Community\App\Models\AlbumCommonity;
use Modules\Community\App\Models\PhotoCommonity;

class ManageAlbumCommunityController extends Controller
{
    protected $model;
    protected $helper;
    protected $handler;
    protected $web;
    protected $community;
    protected $photo;

    public function __construct(AlbumCommonity $model, Helper $helper, Handler $handler, WebRedirect $web, Community $community, PhotoCommonity $photo)
    {
        $this->model = $model;
        $this->helper = $helper;
        $this->handler = $handler;
        $this->web = $web;
        $this->community = $community;
        $this->photo = $photo;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try{
            $page = $request->size ?? 10;
            $data = [
                'content' => 'ManagePeople/Community/Album/index',
                'title' => 'Data Album',
                'albums' =>  $this->model->with(['albumCommonity'])->whereNotNull('t_community_id')->where('t_community_id', auth()->user()->t_community_id)->orderByDesc('id')->paginate($page),
            ];
            return view('ManagePeople.Layouts.wrapper', $data);
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
                'content' => 'ManagePeople/Community/Album/addEdit',
                'title' => 'Create Album',
                // 'community' => $this->community->get(),
                'albums' => null,
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
                // 't_community_id' => 'required',
                'name' => 'required',
                'cover' => 'required|image|file|max:2048|mimes:jpeg,png,jpg',
                'description' => 'required',
                'active' => 'required',
            ]);
            $datas['t_community_id'] = auth()->user()->t_community_id;

            $folder = "dgolf/community/album";
            $column = "cover";

            $model = $this->model->create($datas);

            $this->helper->uploads($folder, $model, $column);

            $com = Community::with([
                'membersCommonity' => function($q) {
                    $q->whereNotNull('users.fcm_token');
                }
            ])->find($datas['t_community_id']);

            $FcmToken = collect();
            foreach($com->membersCommonity as $getFcmToken) {
                $map = $getFcmToken->fcm_token;

                $FcmToken->push($map);
            }

            $this->helper->pushNotification2($FcmToken->toArray(), "Informasi Community", "Album $model->title Telah Ditambahkan", $model->cover, 'COMMUNITY', $datas['t_community_id'], 'album', $model->id);
            DB::commit();
            return $this->web->successReturn('community.album.photo.semua', 'id', $model->id, 'Berhasil Menambah Data, Silahkan Tambahkan Photo');
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
                'content' => 'ManagePeople/Community/Album/addEdit',
                'title' => 'Edit Album',
                'albums' => $this->model->findOrfail($id),
                // 'community' => $this->community->get(),
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
                // 't_community_id' => 'required',
                'name' => 'required',
                'cover' => 'nullable|image|file|max:2048|mimes:jpeg,png,jpg',
                'description' => 'required',
                'active' => 'nullable',
            ]);
            $datas['t_community_id'] = auth()->user()->t_community_id;

            $folder = "dgolf/community/album";
            $column = "cover";

            $model = $this->model->findOrfail($id);

            $model->update($datas);

            $this->helper->uploads($folder, $model, $column);

            DB::commit();
            return $this->web->update('community.manage.album.semua');
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
            return $this->web->successBack('Berhasil Menghapus Data');
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function index_photo(Request $request, $id)
    {
        try{
            $page = $request->size ?? 10;
            $data = [
                'content' => 'ManagePeople/Community/Album/Photo/index',
                'title' => 'Data Photo',
                'photos' =>  $this->photo->with(['photoCommonity'])->where('t_album_id', $id)->orderByDesc('id')->paginate($page),
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
    public function create_photo($album_id)
    {
        try{
            $data = [
                'content' => 'ManagePeople/Community/Album/Photo/addEdit',
                'title' => 'Create Photo',
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
    public function store_photo(Request $request)
    {
        DB::beginTransaction();
        try{
            $datas = $request->validate([
                't_album_id' => 'required',
                'name' => 'required',
                'image' => 'required|image|file|max:2048|mimes:jpeg,png,jpg',
                'active' => 'required',
            ]);

            $folder = "dgolf/community/album/photos";
            $column = "image";

            $model = $this->photo->create($datas);

            $this->helper->uploads($folder, $model, $column);

            DB::commit();
            return $this->web->successReturn('community.manage.album.photo.viewtambah', 'album_id', $model->t_album_id);
        } catch (\Throwable $e) {
            DB::rollBack();
            if($e instanceof ValidationException){
                return $this->web->error_validation($e);
            }
            return $this->handler->handleExceptionWeb($e);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit_photo(string $id)
    {
        try{
            $data = [
                'content' => 'ManagePeople/Community/Album/Photo/addEdit',
                'title' => 'Edit Photo',
                'photos' => $this->photo->with(['photoCommonity'])->findOrfail($id),
            ];
            return view('ManagePeople.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update_photo(Request $request, string $id)
    {
        DB::beginTransaction();
        try{
            $datas = $request->validate([
                't_album_id' => 'required',
                'name' => 'required',
                'image' => 'nullable|image|file|max:2048|mimes:jpeg,png,jpg',
                'active' => 'required',
            ]);

            $folder = "dgolf/community/album";
            $column = "image";

            $model = $this->photo->findOrfail($id);

            $model->update($datas);

            $this->helper->uploads($folder, $model, $column);

            DB::commit();
            return $this->web->updateReturn('community.manage.album.photo.semua', 'album_id', $model->t_album_id);
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
    public function destroy_photo(string $id)
    {
        DB::beginTransaction();
        try{
            $this->photo->findOrfail($id)->delete();
            DB::commit();
            return $this->web->successBack('Berhasil Menghapus Data');
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->handler->handleExceptionWeb($e);
        }
    }
}
