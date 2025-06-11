<?php

namespace Modules\Donasi\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Donasi;
use App\Models\DonaturDonasi;
use App\Models\ImgDonasi;
use App\Services\ApiResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class DonasiController extends Controller
{
    
    /**
     * Display a listing of the resource.
     */

     protected $api;
     protected $donasi;
     protected $donatur;
     protected $img;
     
     public function __construct(ApiResponse $api, Donasi $donasi, DonaturDonasi $donatur, ImgDonasi $ImgDonasi )
     {
         $this->api = $api;
         $this->donasi = $donasi;
         $this->donatur = $donatur;
         $this->img = $ImgDonasi;
     }
 
     public function index(Request $request)
     {
         try {
             $id = $request->query('id'); 
     
             if ($id) {
                 // Detail donasi
                $donasi = Donasi::with(['image_donasi', 'donatur.user'])->findOrFail($id);
                $totalDonatur = $donasi->donatur->count();
     
                $userId = auth()->id();
                $hasDonated = $donasi->donatur->contains('user_id', $userId);

                $nominal_terkini = $donasi->donatur->sum('nominal');

                $persentase_nt = ($donasi->target_sumbangan > 0)
                            ? round(($nominal_terkini / $donasi->target_sumbangan) * 100, 1) : 0;

                $donaturs = $donasi->donatur->map(fn($donatur) => [
                    'id' => $donatur->id,
                    'user_name' => $donatur->user->name ?? null,
                    'nominal' => $donatur->nominal ?? null, 
                    'bukti_donasi' => $donatur->bukti_donasi ?? null, 
                    'note' => $donatur->note ?? null, 
                ]);

                $img_slider = $donasi->image_donasi->map(fn($img) => [
                    'id' => $img->id,
                    'image' => $img->url_image
                ]);
    
                $data = [                    
                    'id' => $donasi->id,
                    'title' => $donasi->title,
                    'start_date' => $donasi->start_date,
                    'end_date' => $donasi->end_date,
                    'target_sumbangan' => $donasi->target_sumbangan,
                    'nominal_terkini' => $nominal_terkini,
                    'persentase_terkini' => $persentase_nt,
                    'description' => $donasi->description,
                    'img_penggalang_dana' => $donasi->img_penggalang_dana,
                    'nama_penggalang_dana' => $donasi->nama_penggalang_dana,
                    'nama_bank' => $donasi->nama_bank,
                    'nomor_rekening' => $donasi->nomor_rekening,
                    'is_donated' => $hasDonated,
                    'total_donatur' => $totalDonatur,
                    'created_by' => $userId,
                    'created_at' => $donasi->created_at,
                    'image_slider' => $img_slider,
                    'donaturs' => $donaturs,
                ];
     
                 return $this->api->list($data, $this->donatur);
             }
             // List polling
 
            $query = Donasi::with(['image_donasi', 'donatur.user'])->orderBy('created_at', 'desc');
 
             $donasis = $query->get()->map(function ($donasi) {
 
                $totalDonatur = $donasi->donatur->count();
        
                $userId = auth()->id();
                $hasDonated = $donasi->donatur->contains('user_id', $userId);

                $nominal_terkini = $donasi->donatur->sum('nominal');

                $donaturs = $donasi->donatur->map(fn($donatur) => [
                    'id' => $donatur->id,
                    'user_name' => $donatur->user->name ?? null,
                    'nominal' => $donatur->nominal ?? null, 
                    'bukti_donasi' => $donatur->bukti_donasi ?? null, 
                    'note' => $donatur->note ?? null, 
                ]);

                $img_slider = $donasi->image_donasi->map(fn($img) => [
                    'id' => $img->id,
                    'image' => $img->url_image
                ]);
    
                return [
                    'id' => $donasi->id,
                    'title' => $donasi->title,
                    'start_date' => $donasi->start_date,
                    'end_date' => $donasi->end_date,
                    'target_sumbangan' => $donasi->target_sumbangan,
                    'nominal_terkini' => $nominal_terkini,
                    'description' => $donasi->description,
                    'img_penggalang_dana' => $donasi->img_penggalang_dana,
                    'nama_penggalang_dana' => $donasi->nama_penggalang_dana,
                    'nama_bank' => $donasi->nama_bank,
                    'nomor_rekening' => $donasi->nomor_rekening,
                    'is_donated' => $hasDonated,
                    'total_donatur' => $totalDonatur,
                    'created_by' => $userId,
                    'created_at' => $donasi->created_at,
                    'image_slider' => $img_slider,
                    'donaturs' => $donaturs,
                ];
             });  
     
             return $this->api->list($donasis, $this->donasi);
         } catch (\Throwable $e) {
             if (config('envconfig.app_debug')) {
                 return $this->api->error_code($e->getMessage(), $e->getCode());
             } else {
                 return $this->api->error_code_log("Internal Server Error", $e->getMessage());
             };
         }
     }
 
 
     public function store(Request $request)
     {
         try {
             $request->validate([
                 'donasi_id' => 'required|exists:t_donasi,id',
                 'note' => 'nullable|string|max:500',
                 'nominal' => 'required|numeric|min:1',
                 'bukti_donasi' => 'required|image|mimes:jpeg,png,jpg|max:2048',
             ]);
 
             $userId = auth()->id();
 
             $donasi = $this->donasi->findOrFail($request->donasi_id);
 
             $alreadyDonate = $this->donatur
                 ->where('user_id', $userId)
                 ->where('donasi_id', $request->donasi_id)
                 ->exists();
 
             if ($alreadyDonate) {
                 return $this->api->error("You have already donate in this event.");
             }
 
             $file = $request->file('bukti_donasi');
             $path = $file->store('rump4t/donasi/bukti-donasi', 's3');
             $url = Storage::disk('s3')->url($path);

             $this->donatur->create([
                 'donasi_id' => $request->donasi_id,
                 'user_id' => $userId,
                 'note' => $request->note,
                 'nominal' => $request->nominal,
                 'bukti_donasi' => $url
             ]);
 
             return $this->api->success("Donation submitted successfully.");
 
         } catch (\Throwable $e) {
             if (config('envconfig.app_debug')) {
                 return $this->api->error_code($e->getMessage(), $e->getCode());
             } else {
                 return $this->api->error_code_log("Internal Server Error", $e->getMessage());
             }
         }
     }
 
    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('donasi::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('donasi::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
