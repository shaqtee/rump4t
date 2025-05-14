<?php

namespace App\Http\Controllers\Admin\Modules\Masters;

use App\Exceptions\Handler;
use Illuminate\Http\Request;
use App\Services\WebRedirect;
use App\Services\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Modules\Masters\App\Models\MastersBanner;
use Modules\Masters\App\Models\MasterReferences;
use Illuminate\Validation\ValidationException;

class MasterBannerSlideController extends Controller
{
    protected $model;
    protected $web;
    protected $handler;
    protected $helper;
    protected $references;

    public function __construct(MastersBanner $model, WebRedirect $web, Handler $handler, Helper $helper, MasterReferences $references)
    {
        $this->model = $model;
        $this->web = $web;
        $this->handler = $handler;
        $this->helper = $helper;
        $this->references = $references;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try{
            $bday_auto = $this->references->where('parameter', 'm_automation')->where('description', 'birthday')->first();
            
            $page = $request->size ?? 10;
            $data = [
                'content' => 'Admin/Masters/BannerSlide/index',
                'title' => 'Data Golf Course',
                'bannerSlide' => $this->model->filter($request)->paginate($page)->appends($request->all()),
                'columns' => $this->model->columnsWeb(),
                'bday_auto' => $bday_auto,
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
                'content' => 'Admin/Masters/BannerSlide/addEdit',
                'title' => 'Add Banner Slide',
                'bannerSlide' => null,
            ];
            return view('Admin.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function activate(Request $request, $desc)
    {
        $is_active = $request->all()['is_active'];
        $references = $this->references->where('parameter', 'm_automation')
            ->where('description', $desc)->first();
        $references->is_active = $is_active;
        $references->save();

        return response()->json([
            'status' => 'success',
            'data' => $references,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            $datas = $request->validate([
                'name' => 'required',
                'image' => 'required|image|file|max:2048|mimes:jpeg,png,jpg',
                'on_view' => 'required',
            ]);

            $model = $this->model->create($datas);

            $folder = "dgolf/master/banner-slide";
            $column = "image";

            $this->helper->uploads($folder, $model, $column);
            DB::commit();
            return $this->web->store('banner-slide.index');
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
                'content' => 'Admin/Masters/BannerSlide/addEdit',
                'title' => 'Add Banner Slide',
                'bannerSlide' => $this->model->findOrfail($id),
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
                'name' => 'required',
                'image' => 'required|image|file|max:2048|mimes:jpeg,png,jpg',
                'on_view' => 'required',
            ]);

            $model = $this->model->findOrfail($id);
            
            $model->update($datas);

            $folder = "dgolf/master/banner-slide";
            $column = "image";

            $this->helper->uploads($folder, $model, $column);
            DB::commit();
            return $this->web->update('banner-slide.index');
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
            return $this->web->destroy('banner-slide.index');
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->handler->handleExceptionWeb($e);
        }
    }
}
