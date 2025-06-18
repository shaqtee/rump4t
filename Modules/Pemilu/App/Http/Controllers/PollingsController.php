<?php

namespace Modules\Pemilu\App\Http\Controllers;

use DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pemilu;
use App\Models\PemiluCandidate;
use App\Models\PemiluPollings;
use App\Services\Helpers\Helper;
use App\Exceptions\Handler;

class PollingsController extends Controller
{
    public function __construct(
        protected Pemilu $pemilu, 
        protected PemiluCandidate $candidates, 
        protected User $users, 
        protected PemiluPollings $pollings,
        protected Helper $helper, 
        protected Handler $handler
    ){}

    public function index(Request $request): JsonResponse
    {
        try {
            $page = $request->input('size', 10);
    
            $data = [
                'pollings' => $this->pemilu
                    ->with([
                        'candidate_users:t_pemilu_id,name,is_active',
                        'polling_users:id,name'
                        ])
                    ->where('is_active', true)
                    ->filter($request)->orderByDesc('id')->paginate($page)->appends($request->all()),
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

    public function pre_vote_users(Request $request): JsonResponse
    {
        try {
            $ids = $request->id_voted_users ?? [];
            $users = $this->users->whereNotIn('id', $ids)
                ->select(['id', 'name'])
                ->where('active', 1)
                ->where('deleted_at', NULL)
                ->where('pass_away_status', false)
                ->get();
    
            return response()->json([
                "status" => "success",
                "users" => $users
            ]);

        } catch (\Exception $e) {
            report($e);

            return response()->json([
                'status' => 'failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function votes(Request $request): JsonResponse
    {
        $validated = $request->validate([
                't_pemilu_id' => 'required',
                't_pemilu_candidates_id' => 'required',
                'user_id' => 'required',
            ]);

        $exist = $this->candidates
            ->where('t_pemilu_id', $validated['t_pemilu_id'])
            ->where('id', $validated['t_pemilu_candidates_id'])
            ->exists();
            
        if(!$exist){
            return response()->json([
                'status' => 'failed',
                'error' => "There is no such an election."
            ], 500);
        }
        
        $voted_user = $this->pollings
            ->where('t_pemilu_id', $validated['t_pemilu_id'])
            ->where('t_pemilu_candidates_id', $validated['t_pemilu_candidates_id'])
            ->where('user_id', $validated['user_id'])
            ->exists();
        
        if($voted_user){
            return response()->json([
                'status' => 'failed',
                'error' => "User has voted in this election."
            ], 500);
        }
        
        DB::beginTransaction();
        try {
            $votes = $this->pollings->create($validated);
            DB::commit();

            return response()->json([
                    "status" => "success",
                    "data" => $votes
                ]);

        } catch (\Exception $e) {
            report($e);
            DB::rollback();

            return response()->json([
                "status" => "failed"
            ], 500);
        }
    }

    public function index_voted(Request $request, $pemilu_id)
    {
        try {
            $page = $request->input('size', 10);
            $voted = $this->pemilu
                ->with([
                    'candidate_users:id,t_pemilu_id,name',
                    'polling_users:id,name'
                    ])
                ->where('id', $pemilu_id)
                ->where('is_active', true)
                ->filter($request)->orderByDesc('id')->paginate($page)->appends($request->all());

            return response()->json([
                    "status" => "success",
                    "data" => $voted
                ]);
            
        } catch (\Exception $e) {
            report($e);
            DB::rollback();

            return response()->json([
                "status" => "failed"
            ], 500);
        }
    }

    public function cancel_voted($voted_id)
    {
        DB::beginTransaction();
        try {
            $pollings = $this->pollings->findOrFail($voted_id);
            $pollings->delete();
            DB::commit();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Voting berhasil dicancel.'
            ]);

        } catch (\Exception $e) {
            report($e);
            DB::rollback();
            return response()->json([
                "status" => "failed"
            ]);
        }
    }
}
