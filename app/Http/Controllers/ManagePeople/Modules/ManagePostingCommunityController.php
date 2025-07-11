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
use Modules\Community\App\Models\PostingCommonity;

class ManagePostingCommunityController extends Controller
{
    protected $model;
    protected $helper;
    protected $handler;
    protected $community;
    protected $web;

    public function __construct(PostingCommonity $model, Helper $helper, Handler $handler, Community $community, WebRedirect $web)
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
                'content' => 'ManagePeople/Community/Posting/index',
                'title' => 'Data Posting',
                'posting' => $this->model->with(['postingCommonity:id,title'])->filter($request)->where('t_community_id', auth()->user()->t_community_id)->orderByDesc('id')->paginate($page),
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
                'content' => 'ManagePeople/Community/Posting/addEdit',
                'title' => 'Create New Posting',
                // 'community' => $this->community->get(),
                'posting' => null,
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
                'title' => 'required|max:20',
                'image' => 'required|image|file|max:2048|mimes:jpeg,png,jpg',
                'content' => 'required',
                'active' => 'required',
            ]);
            $datas['t_community_id'] = auth()->user()->t_community_id;

            $folder = "dgolf/community/posting";
            $column = "image";
            
            $datas['content'] = str_replace("\r", "", $datas['content']);
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


            $this->helper->pushNotification2($FcmToken->toArray(), "Informasi Community", "$model->title", $model->image, 'COMMUNITY', $datas['t_community_id'], 'posting', $model->id);
            DB::commit();
            return $this->web->successReturn('community.manage.posting.semua', 'community_id', $datas['t_community_id']);
        } catch (\Throwable $e) { dd($e);
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
                'content' => 'ManagePeople/Community/Posting/addEdit',
                'title' => 'Update Posting',
                'posting' => $this->model->findOrfail($id),
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
                'title' => 'required|max:20',
                'image' => 'nullable|image|mimes:jpeg,png,jpg',
                'content' => 'required',
                'active' => 'nullable',
            ]);
            $datas['t_community_id'] = auth()->user()->t_community_id;

            $model = $this->model->findOrfail($id);

            $folder = "dgolf/community/posting";
            $column = "image";

            $datas['content'] = str_replace("\r", "", $datas['content']);
            $model->update($datas);

            $this->helper->uploads($folder, $model, $column);

            DB::commit();
            return $this->web->updateReturn('community.manage.posting.semua', 'community_id', $datas['t_community_id']);
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
}
