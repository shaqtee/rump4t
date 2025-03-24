<?php

namespace App\Http\Controllers\Admin\Modules\Masters;

use App\Exceptions\Handler;
use Illuminate\Http\Request;
use App\Services\WebRedirect;
use App\Services\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Modules\Masters\App\Models\MasterWinnerCategory;

class MasterWinnerCategoryController extends Controller
{
    protected $model;
    protected $web;
    protected $handler;
    protected $helper;

    public function __construct(MasterWinnerCategory $model, WebRedirect $web, Handler $handler, Helper $helper)
    {
        $this->model = $model;
        $this->web = $web;
        $this->handler = $handler;
        $this->helper = $helper;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try{
            $page = $request->size ?? 10;
            $data = [
                'content' => 'Admin/Masters/WinnerCategory/index',
                'title' => 'Data Winner Category',
                'winnerCategory' => $this->model->filter($request)->paginate($page)->appends($request->all()),
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
                'content' => 'Admin/Masters/WinnerCategory/addEdit',
                'title' => 'Add Winner Category',
                'winnerCategory' => null,
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
                'code' => 'required',
                'name' => 'required',
                'description' => 'nullable',
            ]);

            $datas['code'] = strtoupper($datas['code']);
            $datas['name'] = ucwords(strtolower($datas['name']));

            $this->model->create($datas);

            DB::commit();
            return $this->web->store('winner-category.index');
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
                'content' => 'Admin/Masters/WinnerCategory/addEdit',
                'title' => 'Add Winner Category',
                'winnerCategory' => $this->model->findOrfail($id),
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
                'code' => 'required',
                'name' => 'required',
                'description' => 'nullable',
            ]);

            $datas['code'] = strtoupper($datas['code']);
            $datas['name'] = ucwords(strtolower($datas['name']));

            $model = $this->model->findOrfail($id);
            
            $model->update($datas);

            DB::commit();
            return $this->web->update('winner-category.index');
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
            return $this->web->destroy('winner-category.index');
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->handler->handleExceptionWeb($e);
        }
    }
}
