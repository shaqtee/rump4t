<?php

namespace App\Http\Controllers\Admin\Modules\SocialMedia;

use App\Exceptions\Handler;
use App\Http\Controllers\Controller;
use App\Services\Helpers\Helper;
use App\Services\WebRedirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Modules\SocialMedia\App\Models\Information;
use Modules\Community\App\Models\EventCommonity;

class InformationController extends Controller
{
    protected $model;
    protected $events;
    protected $helper;
    protected $handler;
    protected $web;

    public function __construct(Information $model, EventCommonity $events, Helper $helper, Handler $handler, WebRedirect $web)
    {
        $this->model = $model;
        $this->events = $events;
        $this->helper = $helper;
        $this->handler = $handler;
        $this->web = $web;
    }

    public function index(Request $request) {
        try{
            $page = $request->size ?? 10;
            $data = [
                'content' => 'Admin/SocialMedia/Information/index',
                'title' => 'Data Information',
                'informations' =>  $this->model->with(['events'])->filter($request)->orderByDesc('id')->paginate($page)->appends($request->all()),
                'columns' => $this->model->columnsWeb()
            ];
            return view('Admin.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function create()
    {
        try{
            $data = [
                'content' => 'Admin/SocialMedia/Information/addEdit',
                'title' => 'Create Information',
                'information' => null,
                'events' => $this->events->get(),
            ];
            return view('Admin.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            $datas = $request->validate([
                'title' => 'required',
                'description' => 'required',
                'image' => 'required|image|file|max:2048|mimes:jpeg,png,jpg',
                'file' => 'required|file|max:2048|mimes:pdf',
                't_event_id' => 'required|integer',
            ]);

            $uploads = [
                [
                    'column' => 'image',
                    'path' => 'dgolf/information/image'
                ],
                [
                    'column' => 'file',
                    'path' => 'dgolf/information/file'
                ]
            ];
            $model = $this->model->create($datas);

            foreach ($uploads as $upload) {
                $this->helper->uploads($upload['path'], $model, $upload['column']);
            }

            DB::commit();
            return $this->web->store('informations.index');
        } catch (\Throwable $e) {
            DB::rollBack();
            if($e instanceof ValidationException){
                return $this->web->error_validation($e);
            }
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function edit(string $id)
    {
        try{
            $data = [
                'content' => 'Admin/SocialMedia/Information/addEdit',
                'title' => 'Edit Information',
                'information' => $this->model->findOrfail($id),
                'events' => $this->events->get(),
            ];
            return view('Admin.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function update(Request $request, string $id)
    {
        DB::beginTransaction();
        try{
            $datas = $request->validate([
                'title' => 'required',
                'description' => 'required',
                'image' => 'required|image|file|max:2048|mimes:jpeg,png,jpg',
                'file' => 'required|file|max:2048|mimes:pdf',
                't_event_id' => 'required|integer',
            ]);

            $uploads = [
                [
                    'column' => 'image',
                    'path' => 'dgolf/information/image'
                ],
                [
                    'column' => 'file',
                    'path' => 'dgolf/information/file'
                ]
            ];

            $model = $this->model->findOrfail($id);

            $model->update($datas);

            foreach ($uploads as $upload) {
                $this->helper->uploads($upload['path'], $model, $upload['column']);
            }

            DB::commit();
            return $this->web->update('informations.index');
        } catch (\Throwable $e) {
            DB::rollBack();
            if($e instanceof ValidationException){
                return $this->web->error_validation($e);
            }
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function destroy(string $id)
    {
        DB::beginTransaction();
        try{
            $this->model->findOrfail($id)->delete();
            DB::commit();
            return $this->web->store('informations.index');
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->handler->handleExceptionWeb($e);
        }
    }
}
