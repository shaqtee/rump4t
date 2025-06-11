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
    
            $candidates = $this->users->whereHas('candidates', function($q) use($pemilu_id) {
                $q->where('t_pemilu_candidates.t_pemilu_id', $pemilu_id);
            });
    
            $ids = [];
            foreach($candidates->get()->toArray() as $c){
                $ids[] = $c['id'];
            }
    
            $data = [
                'pemilu_id' => $pemilu_id,
                'users' => $this->users->whereNotIn('id', $ids)->where('active', 1)->get(),
                'candidates' => $candidates->with('candidates')->filter($request)->orderByDesc('id')->paginate($page)->appends($request->all()),
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

    public function add(Request $request)
    {
        DB::beginTransaction();
        try {
            /* validator: regular */
            $data = $request->validate([
                    't_pemilu_id' => 'required',
                    'user_id' => 'required',
                    'is_active' => 'nullable',
                ]);
            
            /* validasi: unique candidate */
            $exists = $this->candidates
                    ->where('t_pemilu_id', $data['t_pemilu_id'])
                    ->where('user_id', $data['user_id'])
                    ->exists();

            if($exists){
                return response()->json([
                    'status' => 'failed',
                    'error' => "candidate must be unique"
                ], 500);
            }
                
            $new_candidate = $this->candidates->create($data);
            DB::commit();

            return response()->json([
                "status" => "success",
                "data" => $new_candidate
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


