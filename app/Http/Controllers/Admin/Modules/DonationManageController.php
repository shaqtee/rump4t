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
use App\Models\DonaturDonasi;
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
    protected $donatur;

    public function __construct(Donasi $model, Helper $helper, Handler $handler, WebRedirect $web, ImgDonasi $img, DonaturDonasi $donatur)
    {
        $this->model = $model;
        $this->helper = $helper;
        $this->handler = $handler;
        $this->web = $web;
        $this->img = $img;
        $this->donatur = $donatur;
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
                'donation' => $this->model->with(['image_donasi', 'user'])->withCount(['donatur as total_donatur'])->withSum('donatur', 'nominal')  
                    // ->where(function($q){
                    //     if(auth()->user()->t_group_id == 3){
                    //         $q->where('region', auth()->user()->region);
                    //     }
                    // })
                    ->filter($request)->orderByDesc('id')->paginate($page)->appends($request->all()),
                'columns' => $this->model->columnsWeb(),
                'total_donasi' => $this->donatur->sum('nominal'),
                'total_donatur' => $this->donatur->count(),
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
        DB::beginTransaction();
    
        try {
            $request->validate([
                'donasi_id' => 'required|exists:t_pollings,id',
                'url_image.*' => 'nullable|image|max:2048',
                'img_id.*' => 'nullable|integer',
            ]);
    
            $imgIds = $request->img_id ?? [];
            $processedIds = [];
    
            if ($request->hasFile('url_image')) {
                foreach ($request->file('url_image') as $index => $file) {
                    $imageId = $imgIds[$index] ?? null;
    
                    if ($imageId) {
                        $image = $this->img->where('donasi_id', $request->donasi_id)
                            ->where('id', $imageId)
                            ->first();
    
                        if ($image) {
                            if ($file && $file->isValid()) {
                                $path = $file->store('rump4t/donasi/images-slide', 's3');
                                $url = Storage::disk('s3')->url($path);
                                $image->url_image = $url;
                            }
                            $image->save();
                            $processedIds[] = $imageId;
                        }
                    } else {
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
            }
    
            $toKeep = array_merge($imgIds, $processedIds);
            $this->img
                ->where('donasi_id', $request->donasi_id)
                ->whereNotIn('id', $toKeep)
                ->delete();
    
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
        $donation = $this->model->findOrFail($id);

        try{
            $data = [
                'content' => 'Admin/Donasi/addEdit',
                'title' => 'Edit Data Donasi',
                'donation'=> $donation,
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
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date',
                'description' => 'nullable|string',
                'target_sumbangan' => 'nullable|string',
                'img_penggalang_dana' => 'nullable|image|max:2048',
                'nama_penggalang_dana' => 'required|string|max:255',
                'nama_bank' => 'required|string|max:255',
                'nomor_rekening' => 'required|string|max:255',
            ]);

            $donation = Donasi::findOrFail($id);
            $url = null;

            if ($request->hasFile('img_penggalang_dana')) {
                $file = $request->file('img_penggalang_dana');
        
                if ($file->isValid()) {
                    $path = $file->store('rump4t/donasi/pengalang_dana-images', 's3');
                    $url = Storage::disk('s3')->url($path);
                }
            }

            $donation->update([
                'title' => $validated['title'],
                'start_date' => $validated['start_date'] ?? null,
                'end_date' => $validated['end_date'] ?? null,
                'description' => $validated['description'] ?? null,
                'target_sumbangan' => $validated['target_sumbangan'] ?? null,
                'nama_penggalang_dana' => $validated['nama_penggalang_dana'],
                'nama_bank' => $validated['nama_bank'],
                'nomor_rekening' => $validated['nomor_rekening'],
                'created_by' => auth()->id(),
                'created_at' => now(),
            ]);

            if ($url) {
                $updateData['img_penggalang_dana'] = $url;
            }

            DB::commit();
            return redirect()->route('donasi.admin')->with('success', 'Data berhasil diperbarui.');
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
            return $this->web->destroy('donasi.admin');
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function detail_donatur($id)
    {
        $donatur = DonaturDonasi::with(['user'])->where('donasi_id', $id)->paginate(10);

        $firstItem = $donatur->firstItem(); 
        $lastItem = $donatur->lastItem();   // also works
                try{
            $data = [
                'content' => 'Admin/Donasi/DetailDonatur',
                'title' => 'Detail Donatur Donasi',
                'donatur'=> $donatur,
            ];
            return view('Admin.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }
}
