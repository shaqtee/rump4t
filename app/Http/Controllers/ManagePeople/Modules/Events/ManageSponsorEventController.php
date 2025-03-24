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
use Modules\Community\App\Models\EventCommonity;
use Modules\Community\App\Models\SocialMedia;
use Modules\Community\App\Models\SponsorCommonity;

class ManageSponsorEventController extends Controller
{
    protected $model;
    protected $helper;
    protected $handler;
    protected $web;
    protected $community;
    protected $event;
    protected $sosmed;

    public function __construct(SponsorCommonity $model, Helper $helper, Handler $handler, WebRedirect $web, Community $community, EventCommonity $event, SocialMedia $sosmed)
    {
        $this->model = $model;
        $this->helper = $helper;
        $this->handler = $handler;
        $this->web = $web;
        $this->community = $community;
        $this->event = $event;
        $this->sosmed = $sosmed;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $event_id)
    {
        try{
            $page = $request->size ?? 10;
            $data = [
                'content' => 'ManagePeople/Event/Supporting-Partner/index',
                'title' => 'Data Supporting Partner',
                'sponsors' =>  $this->model->with(['sponsorEvent'])->whereNotNull('t_event_id')->where('t_event_id', $event_id)->orderByDesc('id')->paginate($page),
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
                'content' => 'ManagePeople/Event/Supporting-Partner/addEdit',
                'title' => 'Create Supporting Partner',
                'sponsors' => null,
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
                'image' => 'required|image|file|max:2048|mimes:jpeg,png,jpg',
                'description' => 'required|max:200',
                'active' => 'required|IN:1,0',
            ]);
            $datasSosmed = $request->validate([
                'link_website' => 'nullable',
                'link_instagram' => 'nullable',
                'link_facebook' => 'nullable',
                'link_twitter' => 'nullable',
                'link_youtube' => 'nullable',
            ]);

            $folder = "dgolf/community/sponsor";
            $column = "image";

            $model = $this->model->create($datas);

            $datasSosmed['table_name'] = 't_sponsor';
            $datasSosmed['table_id'] = $model->id;
            $datasSosmed['website'] = 'Website';
            $datasSosmed['instagram'] = 'Instagram';
            $datasSosmed['facebook'] = 'Facebook';
            $datasSosmed['twitter'] = 'X';
            $datasSosmed['youtube'] = 'Youtube';

            $this->sosmed->create($datasSosmed);
            $this->helper->uploads($folder, $model, $column);

            DB::commit();
            return $this->web->successReturn('event.manage.sponsor.semua', 'event_id', $model->t_event_id);
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
                'content' => 'ManagePeople/Event/Supporting-Partner/addEdit',
                'title' => 'Edit Supporting Partner',
                'sponsors' => $this->model->with(['sponsorEvent', 'sosialMedia'])->findOrfail($id),
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
                'image' => 'required|image|file|max:2048|mimes:jpeg,png,jpg',
                'description' => 'required|max:200',
                'active' => 'required|IN:1,0',
            ]);
            $datasSosmed = $request->validate([
                'link_website' => 'nullable',
                'link_instagram' => 'nullable',
                'link_facebook' => 'nullable',
                'link_twitter' => 'nullable',
                'link_youtube' => 'nullable',
            ]);

            $folder = "dgolf/community/sponsor";
            $column = "image";

            $model = $this->model->with(['socialMedia'])->findOrfail($id);
            $sosmed = $this->sosmed->findOrfail($model->socialMedia->id);

            $model->update($datas);
            $sosmed->update($datasSosmed);

            $this->helper->uploads($folder, $model, $column);

            DB::commit();
            return $this->web->updateReturn('event.manage.sponsor.semua', 'event_id', $model->t_event_id);
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
            $model = $this->model->with(['socialMedia'])->findOrfail($id);
            $this->sosmed->findOrfail($model->socialMedia->id)->delete();

            $model->delete();
            DB::commit();
            return $this->web->successBack('Berhasil Menghapus Data');
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->handler->handleExceptionWeb($e);
        }
    }
}
