<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Exceptions\Handler;
use Illuminate\Http\Request;
use App\Services\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\WebRedirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Modules\Community\App\Models\Community;
use Modules\Community\App\Models\PostingCommonity;
use Modules\SocialMedia\App\Models\Post;

class ImgSliderCommunityController extends Controller
{
    protected $model;
    protected $helper;
    protected $handler;
    protected $community;
    protected $web;

    public function __construct(Post $model, Helper $helper, Handler $handler, Community $community, WebRedirect $web)
    {
        $this->model = $model;
        $this->helper = $helper;
        $this->handler = $handler;
        $this->community = $community;
        $this->web = $web;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try{
            $page = $request->size ?? 10;
            $data = [
                'content' => 'Admin/Community/Posting/index',
                'title' => 'Data Posting',
                'posting' => $this->model->with(['postingCommonity:id,title'])->whereNotNull('t_community_id')->filter($request)->orderByDesc('id')->paginate($page)->appends($request->all()),
                'columns' => $this->model->columnsWeb(),
            ];

            return view('Admin.Layouts.wrapper', $data);

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
                'content' => 'Admin/Community/Img_slider_community/addEdit',
                'title' => 'Add New Image',
                'community' => $this->community->get(),
                'posting' => null,
            ];

            return view('Admin.Layouts.wrapper', $data);
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
                't_community_id' => 'required',
                'title' => 'required|max:20',
                'image' => 'required|image|file|max:2048|mimes:jpeg,png,jpg',
                'desc' => 'required',
            ]);

            $datas['id_user'] = auth()->id();

            $file = $request->file('image');
            if ($file && $file->isValid()) {
                $path = $file->store('rump4t/community/socialmedia', 's3');
                $url = Storage::disk('s3')->url($path);
                $datas['url_cover_image'] = $url; 
            }

            $model = $this->model->create($datas);

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


            $this->helper->pushNotification2($FcmToken->toArray(), "Informasi Community", "$model->title", $model->image, 'COMMUNITY', $datas['t_community_id'], 'posting', $model->id);
            DB::commit();
            return $this->web->store('community.posting.semua');
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
    public function show($id)
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
                'content' => 'Admin/Community/Posting/addEdit',
                'title' => 'Update Posting',
                'posting' => $this->model->findOrfail($id),
                'community' => $this->community->get(),
            ];
            
            return view('Admin.Layouts.wrapper', $data);
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
                't_community_id' => 'required',
                'title' => 'required|max:20',
                'image' => 'nullable|image|file|max:2048|mimes:jpeg,png,jpg',
                'desc' => 'required',
            ]);

            $datas['id_user'] = auth()->id();
            $model = $this->model->findOrfail($id);

            $file = $request->file('image');
            if ($file && $file->isValid()) {
                $path = $file->store('rump4t/community/socialmedia', 's3');
                $url = Storage::disk('s3')->url($path);
                $datas['url_cover_image'] = $url; 
            }
            $model->update($datas);

            DB::commit();
            return $this->web->update('community.posting.semua');
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
            return $this->web->destroy('community.posting.semua');
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->handler->handleExceptionWeb($e);
        }
    }
}
