<?php

namespace Modules\SocialMedia\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ApiResponse;
use App\Services\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\SocialMedia\App\Models\CandidateVotes;
use Modules\SocialMedia\App\Models\ElectionCandidates;
use Modules\SocialMedia\App\Models\ElectionPersonResponsible;
use Modules\SocialMedia\App\Models\Elections;
use Modules\SocialMedia\App\Services\Interfaces\SocialMediaInterface;

class ElectionsController extends Controller
{
    protected $model;
    protected $electionCandidates;
    protected $electionPersonResponsible;
    protected $candidateVotes;
    protected $user;
    protected $helper;
    protected $api;
    protected $interface;

    public function __construct(Elections $model, ElectionCandidates $electionCandidates, ElectionPersonResponsible $electionPersonResponsible, CandidateVotes $candidateVotes, User $user, Helper $helper, ApiResponse $api, SocialMediaInterface $interface)
    {
        $this->model = $model;
        $this->electionCandidates = $electionCandidates;
        $this->electionPersonResponsible = $electionPersonResponsible;
        $this->candidateVotes = $candidateVotes;
        $this->user = $user;
        $this->helper = $helper;
        $this->api = $api;
        $this->interface = $interface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $page = $request->size ?? 10;
            $index = $this->model->with([
                        'candidates' => function($q) {
                            $q->with(['user:id,name']);
                        }
                    ])->filter($request)->orderByDesc('id')->paginate($page);
            return $this->api->list($index, $this->model);
        } catch(\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            }
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
                't_election_id' => 'required|integer',
                't_election_candidate_id' => 'required|integer'
            ]);

            $datas['t_user_id'] = auth()->user()->id;

            $election = $this->model->find($datas['t_election_id']);
            if (!$election) return $this->api->error("Data Election Tidak Ditemukan");

            $electionCandidate = $this->electionCandidates->find($datas['t_election_candidate_id']);
            if (!$electionCandidate) return $this->api->error("Data Candidate Tidak Ditemukan");

            if ($election->end_date < now()) return $this->api->error("Waktu Telah Berakhir");
            if ($election->start_date > now()) return $this->api->error("Waktu Belum Dimulai");

            $checkVoted = $this->candidateVotes->where('t_election_id', $datas['t_election_id'])->where('t_user_id', $datas['t_user_id'])->first();
            if ($checkVoted) {
                $result = $checkVoted->update([
                    't_election_candidate_id' => $datas['t_election_candidate_id'],
                ]);
            } else {
                $result = $this->candidateVotes->create($datas);
            }
            DB::commit();
            return $this->api->success($result);
        } catch(\Throwable $e) {
            DB::rollBack();
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
        try {
            $responsible = $this->electionPersonResponsible->where('t_election_id', $id)->where('t_user_id', auth()->user()->id)->first();
            $show = $this->model->find($id);
            if (!$show) return $this->api->error();

            if ($responsible || (!$responsible && $show->is_opend)) {
                $show = $this->model->with([
                    'candidates' => function($q) {
                        $q->with(['user:id,name', 'voters.user:id,name'])->withCount('voters');
                    },
                ])->find($id);

                if ($responsible) {
                    $show->flag_button_update = true;
                } else if ((!$responsible && $show->is_opend)) {
                    $show->flag_button_update = false;
                }
            } else {
                $show = $this->model->with([
                    'candidates' => function($q) {
                        $q->with(['user:id,name']);
                    },
                ])->find($id);

                $show->flag_button_update = false;
            }
            return $this->api->success($show);
        } catch(\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            }
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {
        $responsible = $this->electionPersonResponsible->where('t_election_id', $id)->where('t_user_id', auth()->user()->id)->first();
        if (!$responsible) return $this->api->error('You Are Not The One In Charge');

        $update = $this->model->find($id);
        $update->update([
            'is_opend' => true,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
