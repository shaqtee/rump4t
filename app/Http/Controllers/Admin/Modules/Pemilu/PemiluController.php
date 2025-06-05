<?php

namespace App\Http\Controllers\Admin\Modules\Pemilu;

use DB;
use App\Http\Controllers\Controller;
use App\Services\WebRedirect;
use App\Services\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Exceptions\Handler;
use App\Models\Pemilu;

class PemiluController extends Controller
{
    protected $model;
    protected $helper;
    protected $handler;
    protected $web;
    protected $option;
    protected $vote;

    public function __construct(
        Pemilu $model, 
        Helper $helper, Handler $handler, WebRedirect $web)
    {
        $this->model = $model;
        $this->helper = $helper;
        $this->handler = $handler;
        $this->web = $web;
    }

    public function index_admin(Request $request)
    {
        try{
            $page = $request->size ?? 10;
            $data = [
                'content' => 'Admin/Pemilu/index',
                'title' => 'Data Pemilu',
                'pollings' => $this->model
                    ->filter($request)->orderByDesc('id')->paginate($page)->appends($request->all()),
                'columns' => $this->model->columns(),
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
                'content' => 'Admin/Pemilu/addEdit',
                'title' => 'Add Data Pemilu',
                'pollings' => null,
            ];
            return view('Admin.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date',
                'is_active' => 'required|bool',
            ]);

            $validated['created_by'] = auth()->id();
            
            Pemilu::create($validated);

            DB::commit();
            return $this->web->store('pemilu.semua');

        } catch (\Throwable $e) {
            DB::rollBack();
            if($e instanceof ValidationException){
                return $this->web->error_validation($e);
            }
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function edit_admin($id)
    {
        $polling = $this->model->findOrFail($id);

        try{
            $data = [
                'content' => 'Admin/Pemilu/addEdit',
                'title' => 'Edit Data Pemilu',
                'pollings'=> $polling,
            ];
            return view('Admin.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function update_admin(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date',
                'is_active' => 'required|bool',
            ]);

            $polling = Pemilu::findOrFail($id);
            $polling->update($validated);

            DB::commit();

            return $this->web->success('pemilu.semua', 'Pemilu berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            if ($e instanceof ValidationException) {
                return back()->withErrors($e->validator)->withInput();
            }
            return back()->with('error', 'Terjadi kesalahan.');
        }
    }

    public function destroy(string $id)
    {
        DB::beginTransaction();
        try{

            $polling = $this->model->findOrFail($id);
            $polling->delete();

            DB::commit();
            return $this->web->destroy('pemilu.semua');
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->handler->handleExceptionWeb($e);
        }
    }
}
