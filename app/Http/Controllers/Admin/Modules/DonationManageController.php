<?php

namespace App\Http\Controllers\Admin\Modules;

use Carbon\Carbon;
use App\Models\User;
use App\Exceptions\Handler;
use Illuminate\Http\Request;
use App\Services\WebRedirect;
use Illuminate\Validation\Rule;
use App\Services\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Donasi;
use App\Models\ImgDonasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class DonationManageController extends Controller
{
    protected $model;
    protected $helper;
    protected $handler;
    protected $web;
    protected $img;
    // protected $vote;

    public function __construct(Donasi $model, Helper $helper, Handler $handler, WebRedirect $web, ImgDonasi $img)
    {
        $this->model = $model;
        $this->helper = $helper;
        $this->handler = $handler;
        $this->web = $web;
        $this->img = $img;
        // $this->vote = $vote;
    }
    /**
     * Display a listing of the resource.
     */
    public function index_admin(Request $request)
    {
        try{
            $page = $request->size ?? 10;
            $data = [
                'content' => 'Admin/Donasi/index',
                'title' => 'Data Donasi',
                'donation' => $this->model->with(['image_donasi', 'user'])
                    // ->where(function($q){
                    //     if(auth()->user()->t_group_id == 3){
                    //         $q->where('region', auth()->user()->region);
                    //     }
                    // })
                    ->filter($request)->orderByDesc('id')->paginate($page)->appends($request->all()),
                'columns' => $this->model->columnsWeb(),
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
                'content' => 'Admin/Donasi/addEdit',
                'title' => 'Add Data Donasi',
                'donation' => null,
            ];
            return view('Admin.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function store(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date',
                'description' => 'nullable|string',
                'target_sumbangan' => 'nullable|string',
                'img_penggalang_dana' => 'nullable|image|max:2048',
                'nama_penggalang_dana' => 'required|string|max:255',
                'nama_bank' => 'required|string|max:255',
                'nomor_rekening' => 'required|string|max:255',
            ]);

            $url = null;

            if ($request->hasFile('img_penggalang_dana')) {
                $file = $request->file('img_penggalang_dana');
        
                if ($file->isValid()) {
                    $path = $file->store('rump4t/donasi/pengalang_dana-images', 's3');
                    $url = Storage::disk('s3')->url($path);
                }
            }

            $donasi = Donasi::create([
                'title' => $validated['title'],
                'start_date' => $validated['start_date'] ?? null,
                'end_date' => $validated['end_date'] ?? null,
                'description' => $validated['description'] ?? null,
                'target_sumbangan' => $validated['target_sumbangan'] ?? null,
                'img_penggalang_dana' => $url,
                'nama_penggalang_dana' => $validated['nama_penggalang_dana'],
                'nama_bank' => $validated['nama_bank'],
                'nomor_rekening' => $validated['nomor_rekening'],
                'created_by' => auth()->id(),
                'created_at' => now(),
            ]);

            DB::commit();
            return $this->web->store('donasi.admin');

        } catch (\Throwable $e) {
            DB::rollBack();
            if($e instanceof ValidationException){
                return $this->web->error_validation($e);
            }
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function create_image($id)
    {
        try{
            $img = ImgDonasi::where('donasi_id', $id)->get();

            $data = [
                'content' => 'Admin/Donasi/addEdit_ImgDonasi',
                'title' => 'Add Image Donasi',
                'donasi_id' => $id,
                'image' => $img,
            ];
            return view('Admin.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function store_image(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
    
        try {
            $request->validate([
                'donasi_id' => 'required|exists:t_pollings,id',
                'url_image.*' => 'nullable|image|max:2048',
                'img_id.*' => 'nullable|integer',
            ]);
    
            $imgIds = $request->img_id ?? [];
            $existingImgIds = $this->img
                ->where('donasi_id', $request->donasi_id)
                ->pluck('id')
                ->toArray();

            $processedIds = [];

            foreach ($request->url_image as $index => $file) {
                $imageId = $imgIds[$index] ?? null;
    
                if ($imageId && in_array($imageId, $existingImgIds)) {
                    // Update gambar lama
                    $image = $this->img->find($imageId);
    
                    if ($file && $file->isValid()) {
                        $path = $file->store('rump4t/donasi/images-slide', 's3');
                        $url = Storage::disk('s3')->url($path);
                        $image->url_image = $url;
                    }
    
                    $image->save();
                    $processedIds[] = $imageId;
                } else {
                    // Tambah gambar baru
                    if ($file && $file->isValid()) {
                        $path = $file->store('rump4t/donasi/images-slide', 's3');
                        $url = Storage::disk('s3')->url($path);
    
                        $newImage = $this->img->create([
                            'donasi_id' => $request->donasi_id,
                            'url_image' => $url,
                        ]);
    
                        $processedIds[] = $newImage->id;
                    }
                }
            }
    
            $toDelete = array_diff($existingImgIds, $processedIds);
            if (!empty($toDelete)) {
                $this->img->whereIn('id', $toDelete)->delete();
            }
    
            DB::commit();
            return $this->web->store('donasi.admin');
    
        } catch (\Throwable $e) {
            DB::rollBack();
    
            if ($e instanceof \Illuminate\Validation\ValidationException) {
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
                'content' => 'Admin/Polling/addEdit',
                'title' => 'Edit Data Polling',
                'pollings'=> $polling,
                'regions' => Region::where('parameter', 'm_region')->get(),
                'communities' => Community::all(),
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
                'title_description' => 'nullable|string',
                'question' => 'required|string',
                'question_description' => 'nullable|string',
                'start_date' => 'nullable|date',
                'deadline' => 'nullable|date',
                'target_roles' => 'nullable|array',
                'target_roles.*' => 'string',
                'target_region_id' => 'nullable|integer',
                'target_community_id' => 'nullable|integer',
            ]);

            $polling = Polling::findOrFail($id);
            $polling->update([
                'title' => $validated['title'],
                'title_description' => $validated['title_description'] ?? null,
                'question' => $validated['question'],
                'question_description' => $validated['question_description'] ?? null,
                'start_date' => $validated['start_date'] ?? null,
                'deadline' => $validated['deadline'] ?? null,
                'target_roles' => $validated['target_roles']
                    ? '{' . implode(',', $validated['target_roles']) . '}'
                    : null,
                'target_region_id' => $validated['target_region_id'] ?? null,
                'target_community_id' => $validated['target_community_id'] ?? null,
            ]);

            DB::commit();
            return redirect()->route('polling_admin.index')->with('success', 'Polling berhasil diperbarui.');
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
            return $this->web->destroy('polling.admin');
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->handler->handleExceptionWeb($e);
        }
    }

}
