<?php

namespace App\Http\Controllers\Admin\Modules\Pemilu;

use DB;
use App\Http\Controllers\Controller;
use App\Services\WebRedirect;
use App\Services\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Exceptions\Handler;
use App\Models\User;
use App\Models\Pemilu;
use App\Models\PemiluCandidate;

class PemiluController extends Controller
{
    protected $model;
    protected $users;
    protected $candidates;
    protected $helper;
    protected $handler;
    protected $web;

    public function __construct(
        Pemilu $model, PemiluCandidate $candidates, User $users,
        Helper $helper, Handler $handler, WebRedirect $web)
    {
        $this->model = $model;
        $this->users = $users;
        $this->candidates = $candidates;
        $this->helper = $helper;
        $this->handler = $handler;
        $this->web = $web;
    }

    /**
     * CRUD PEMILU
     */
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

    /**
     * Candidate
     */
    public function user_candidate(Request $request, $pemilu_id)
    { 
        $candidates = $this->candidates->where('t_pemilu_id', $pemilu_id);  
        try{
            $page = $request->size ?? 10;
            $data = [
                'content' => 'Admin/Pemilu/Candidate/index',
                'title' => 'Data User Candidates',
                'candidates' => $candidates->filter($request)->orderByDesc('id')->paginate($page)->appends($request->all()),
                'columns' => $this->candidates->columnsWeb(),
                'pemilu_id' => $pemilu_id,
            ];

            return view('Admin.Layouts.wrapper', $data);

        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function create_candidate($pemilu_id)
    {
        try{

            $data = [
                'content' => 'Admin/Pemilu/Candidate/addEdit',
                'title' => 'Create Candidate',
                'candidate' => null,
                'pemilu_id' => $pemilu_id,
            ];

            return view('Admin.Layouts.wrapper', $data);

        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function add_candidate(Request $request, $pemilu_id)
    {
        DB::beginTransaction();
        try {
            $datas = $request->validate([
                    't_pemilu_id' => 'required',
                    'name' => 'required',
                    'birth_place' => 'required',
                    'birth_date' => 'required',
                    'riwayat_pendidikan' => 'required',
                    'riwayat_pekerjaan' => 'required',
                    'visi_misi' => 'required',
                    'is_active' => 'nullable',
                ]);
                
            $folder = "rump4t/candidate/profile";
            $column = "image";

            $model = $this->candidates->create($datas);

            $this->helper->uploads($folder, $model, $column);
            DB::commit();

            return $this->web->successReturn('pemilu.candidate', 'pemilu_id', $pemilu_id);
        } catch (\Throwable $e) {
            report($e);
            DB::rollBack();
            if($e instanceof ValidationException){
                return $this->web->error_validation($e);
            }
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function edit_candidate($id,$pemilu_id)
    {
        try{
            $candidate = $this->candidates->findOrFail($id);
            $data = [
                'content' => 'Admin/Pemilu/Candidate/addEdit',
                'title' => 'Create Candidate',
                'candidate' => $candidate,
                'pemilu_id' => $pemilu_id,
            ];

            return view('Admin.Layouts.wrapper', $data);

        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function update_candidate(Request $request, $id)
    {
        $datas = $request->validate([
                    't_pemilu_id' => 'required',
                    'name' => 'required',
                    'birth_place' => 'required',
                    'birth_date' => 'required',
                    'riwayat_pendidikan' => 'required',
                    'riwayat_pekerjaan' => 'required',
                    'visi_misi' => 'required',
                    'is_active' => 'nullable',
                ]);
                
            $folder = "rump4t/candidate/profile";
            $column = "image";

            $model = $this->candidates->findOrfail($id);

            $model->update($datas);

            $this->helper->uploads($folder, $model, $column);
            DB::commit();

            return $this->web->successReturn('pemilu.candidate', 'pemilu_id', $request->t_pemilu_id);
    }

    public function left_candidate($id)
    {
        DB::beginTransaction();
        try {
            $pivot = $this->candidates->find($id);
            $pivot->delete();
            DB::commit();
            
            return $this->web->destroyBack('Berhasil melepaskan Kandidat dari Pemilu.');
        } catch (\Throwable $e) {
            report($e);
            DB::rollBack();

            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function activate_candidate(Request $request)
    {
        $pivot = $this->candidates->find($request->id);
        $pivot->is_active = !$pivot->is_active;
        $pivot->save();

        return response()->json([
            'status' => 'success',
            'data' => $pivot,
        ]);
    }
}
