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
use App\Models\PemiluPollings;

class PemiluPollingsController extends Controller
{
    protected $model;
    protected $users;
    protected $candidates;
    protected $pollings;
    protected $helper;
    protected $handler;
    protected $web;

    public function __construct(
        Pemilu $model, PemiluCandidate $candidates, User $users, PemiluPollings $pollings,
        Helper $helper, Handler $handler, WebRedirect $web)
    {
        $this->model = $model;
        $this->users = $users;
        $this->candidates = $candidates;
        $this->pollings = $pollings;
        $this->helper = $helper;
        $this->handler = $handler;
        $this->web = $web;
    }

    public function index(Request $request)
    {
        try{
            $page = $request->size ?? 10;
            $data = [
                'content' => 'Admin/Pemilu/Pollings/index',
                'title' => 'Data Pollings',
                'pollings' => $this->model
                    ->with([
                        'candidate_users',
                        'polling_users'
                        ])
                    ->where('is_active', true)
                    ->filter($request)->orderByDesc('id')->paginate($page)->appends($request->all()),
                'columns' => $this->model->columnsPollings(),
            ];

            return view('Admin.Layouts.wrapper', $data);

        } catch (\Exception $e) {
            report($e);
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function index_voted(Request $request, $pemilu_id)
    {
        // dd(
        //     $this->model
        //             ->with([
        //                 'candidate_users',
        //                 'polling_users:id,name'
        //                 ])
        //             ->where('id', $pemilu_id)
        //             ->where('is_active', true)->get()
        //             );
        try{
            $page = $request->size ?? 10;
            $data = [
                'content' => 'Admin/Pemilu/Pollings/index_voted',
                'title' => 'Data Pollings',
                'pollings' => $this->model
                    ->with([
                        'candidate_users',
                        'polling_users:id,name'
                        ])
                    ->where('id', $pemilu_id)
                    ->where('is_active', true)
                    ->filter($request)->orderByDesc('id')->paginate($page)->appends($request->all()),
                'columns' => $this->model->columnsPollings(),
            ];

            return view('Admin.Layouts.wrapper', $data);

        } catch (\Exception $e) {
            report($e);
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function ajax_user_vote(Request $request)
    {
        $ids = $request->voted_users ?? [];
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
    }

    public function vote(Request $request)
    {
        $validated = $request->validate([
                't_pemilu_id' => 'required',
                't_pemilu_candidates_id' => 'required',
                'user_id' => 'required',
            ]);
        
        DB::beginTransaction();
        try {
            $this->pollings->create($validated);
            DB::commit();

            return $this->web->store('pemilu.pollings');

        } catch (\Exception $e) {
            report($e);
            DB::rollback();
            return response()->json([
                "status" => "failed"
            ]);
        }
    }

    public function cancel_voted($voted_id)
    {
        DB::beginTransaction();
        try {
            $pollings = $this->pollings->findOrFail($voted_id);
            $pollings->delete();
            DB::commit();
            
            return redirect()->back()->with('success','Vote has been cancelled.');

        } catch (\Exception $e) {
            report($e);
            DB::rollback();
            return response()->json([
                "status" => "failed"
            ]);
        }
    }
}
