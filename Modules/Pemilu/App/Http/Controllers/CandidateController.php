<?php

namespace Modules\Pemilu\App\Http\Controllers;

use DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pemilu;
use App\Models\PemiluCandidate;
use App\Services\Helpers\Helper;
use App\Exceptions\Handler;

class CandidateController extends Controller
{
    public function __construct(
        protected Pemilu $model,
        protected User $users,
        protected PemiluCandidate $candidates,
        protected Helper $helper,
        protected Handler $handler
    ){}

    public function index(Request $request, $pemilu_id): JsonResponse
    {
        try {
            $page = $request->input('size', 10);

            $candidates = $this->candidates->where('t_pemilu_id', $pemilu_id);
    
            $data = [
                'pemilu_id' => $pemilu_id,
                'candidates' => $candidates->filter($request)->orderByDesc('id')->paginate($page)->appends($request->all()),
            ];
    
            return response()->json([
                "status" => "success",
                "data" => $data
            ]);
        } catch (\Exception $e) {
            report($e);

            return response()->json([
                'status' => 'failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(PemiluCandidate $candidate)
    {
        try {
            return response()->json([
                "status" => "success",
                "data" => $candidate
            ]);
            
        } catch (\Exception $e) {
            report($e);

            return response()->json([
                'status' => 'failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function add(Request $request)
    {
        DB::beginTransaction();
        try {
            /* validator: regular */
            $data = $request->validate([
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
                
            $model = $this->candidates->create($data);

            $this->helper->uploads($folder, $model, $column);
            DB::commit();

            return response()->json([
                "status" => "success",
                "data" => $model
            ]);
        } catch (\Throwable $e) {
            report($e);
            DB::rollBack();
            
            return response()->json([
                'status' => 'failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, PemiluCandidate $candidate)
    {
        // return response()->json($request->input('t_pemilu_id'));
        DB::beginTransaction();
        try {
            /* validator: regular */
            $datas = $request->validate([
                    't_pemilu_id'           => 'required',
                    'name'                  => 'sometimes|required',
                    'birth_place'           => 'sometimes|required',
                    'birth_date'            => 'sometimes|required',
                    'riwayat_pendidikan'    => 'sometimes|required',
                    'riwayat_pekerjaan'     => 'sometimes|array',
                    'visi_misi'             => 'sometimes|required',
                    'is_active'             => 'sometimes|nullable',
                ]);

            $check = $this->candidates
                ->where('id', $candidate->id)
                ->where('t_pemilu_id', $datas['t_pemilu_id'])
                ->first();
            
            if(!$check){
                return response()->json([
                    'status' => 'failed',
                    'error' => 'Kandidat tidak ada dalam pemilihan ini.'
                ], 500);
            }
    
            $folder = "rump4t/candidate/profile";
            $column = "image";
            
            $candidate->update($datas);

            $this->helper->uploads($folder, $candidate, $column);
            DB::commit();

            return response()->json([
                "status" => "success",
                "data" => $candidate
            ]);

        } catch (\Throwable $e) {
            report($e);
            DB::rollBack();
            
            return response()->json([
                'status' => 'failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function left(PemiluCandidate $candidate)
    {
        try {
            DB::beginTransaction();
            $candidate->delete();
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Kandidat berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            report($e);
            DB::rollback();

            return response()->json([
                'status' => 'failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function activate(Request $request, PemiluCandidate $candidate)
    {
        try {
            DB::beginTransaction();
            $data = $request->validate([
                "is_active" => "required|bool"
            ]);

            $candidate->update($data);
            DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => $candidate
            ]);

        } catch (\Exception $e) {
            report($e);
            DB::rollback();

            return response()->json([
                'status' => 'failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}


