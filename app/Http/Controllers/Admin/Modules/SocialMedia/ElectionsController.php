<?php

namespace App\Http\Controllers\Admin\Modules\SocialMedia;

use App\Exceptions\Handler;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Helpers\Helper;
use App\Services\WebRedirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Modules\Masters\App\Models\MasterConfiguration;
use Modules\SocialMedia\App\Models\CandidateVotes;
use Modules\SocialMedia\App\Models\Elections;
use Modules\SocialMedia\App\Models\ElectionCandidates;
use Modules\SocialMedia\App\Models\ElectionPersonResponsible;

class ElectionsController extends Controller
{
    protected $model;
    protected $electionCandidates;
    protected $electionPersonResponsible;
    protected $candidateVotes;
    protected $user;
    protected $mConfig;
    protected $helper;
    protected $handler;
    protected $web;

    public function __construct(Elections $model, ElectionCandidates $electionCandidates, ElectionPersonResponsible $electionPersonResponsible, CandidateVotes $candidateVotes, User $user, MasterConfiguration $mConfig, Helper $helper, Handler $handler, WebRedirect $web)
    {
        $this->model = $model;
        $this->electionCandidates = $electionCandidates;
        $this->electionPersonResponsible = $electionPersonResponsible;
        $this->candidateVotes = $candidateVotes;
        $this->user = $user;
        $this->mConfig = $mConfig;
        $this->helper = $helper;
        $this->handler = $handler;
        $this->web = $web;
    }

    public function index(Request $request) {
        try{
            $page = $request->size ?? 10;
            $data = [
                'content' => 'Admin/SocialMedia/Election/index',
                'title' => 'Data Election',
                'elections' =>  $this->model->filter($request)->orderByDesc('id')->paginate($page)->appends($request->all()),
                'columns' => $this->model->columnsWeb()
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
                'content' => 'Admin/SocialMedia/Election/addEdit',
                'title' => 'Create Election',
                'election' => null,
                'users' => $this->user->where('flag_done_profile', 1)->get(),
                'candidates' => $this->user->where('flag_done_profile', 1)->get()
            ];
            return view('Admin.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            $datas = $request->validate([
                'title' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                't_candidates_id' => 'required|array',
                't_person_responsible_id' => 'required|array',
            ]);

            $store = $this->model->create($datas);
            if (isset($datas['t_candidates_id'])) {
                $this->storeCandidate($store, $datas['t_candidates_id']);
            }
            if (isset($datas['t_person_responsible_id'])) {
                $this->storePersonResponsible($store, $datas['t_person_responsible_id']);
            }
            DB::commit();
            return $this->web->successReturn('elections.edit', 'election', $store->id);
        } catch (\Throwable $e) {
            DB::rollBack();
            if($e instanceof ValidationException){
                return $this->web->error_validation($e);
            }
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function edit(string $id)
    {
        try{
            $data = [
                'content' => 'Admin/SocialMedia/Election/addEdit',
                'title' => 'Edit Election',
                'election' => $this->model->with(['candidates' => function ($query) {
                    $query->with(['user:id,name']); //->withCount('voters');
                }, 'personResponsible' => function($query) {
                    $query->with(['user:id,name']);
                }])->findOrfail($id),
                'users' => $this->user->where('flag_done_profile', 1)->get()
            ];
            return view('Admin.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function update(Request $request, string $id)
    {
        DB::beginTransaction();
        try{
            $datas = $request->validate([
                'title' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                't_candidates_id' => 'nullable|array',
                't_person_responsible_id' => 'required|array',
            ]);

            $update = $this->model->findOrfail($id);

            if (isset($datas['t_candidates_id'])) {
                $this->storeCandidate($update, $datas['t_candidates_id']);
            }
            if (isset($datas['t_person_responsible_id'])) {
                $this->storePersonResponsible($update, $datas['t_person_responsible_id']);
            }
            $update->update($datas);
            DB::commit();
            return $this->web->updateReturn('elections.edit', 'election', $update->id);
        } catch (\Throwable $e) {
            DB::rollBack();
            if($e instanceof ValidationException){
                return $this->web->error_validation($e);
            }
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function destroy(string $id)
    {
        DB::beginTransaction();
        try{
            $this->model->findOrfail($id)->delete();
            DB::commit();
            return $this->web->store('elections.index');
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function storeCandidate($electionId, array $t_candidates_id)
    {
        DB::beginTransaction();
        try{
            $candidates = $this->user->whereIn('id', $t_candidates_id)->where('flag_done_profile', 1)->get();
            if ($candidates) {
                $insertElectionCandidates = collect();
                $candidates->each(function($item, $key) use($insertElectionCandidates, $electionId){
                    $insertElectionCandidates->push([
                        't_election_id' => $electionId->id,
                        't_user_id' => $item->id,
                    ]);
                });

                $this->electionCandidates->insert($insertElectionCandidates->toArray());
            }
            DB::commit();
            return true;
        } catch (\Throwable $e) {
            DB::rollBack();
            if($e instanceof ValidationException){
                return $this->web->error_validation($e);
            }
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function storePersonResponsible($electionId, array $t_person_responsible_id)
    {
        DB::beginTransaction();
        try{
            $candidates = $this->user->whereIn('id', $t_person_responsible_id)->where('flag_done_profile', 1)->get();
            if ($candidates) {
                $insertPersonResponsible = collect();
                $candidates->each(function($item, $key) use($insertPersonResponsible, $electionId){
                    $insertPersonResponsible->push([
                        't_election_id' => $electionId->id,
                        't_user_id' => $item->id,
                    ]);
                });

                $this->electionPersonResponsible->insert($insertPersonResponsible->toArray());
            }
            DB::commit();
            return true;
        } catch (\Throwable $e) {
            DB::rollBack();
            if($e instanceof ValidationException){
                return $this->web->error_validation($e);
            }
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function updateCandidate(Request $request, string $id)
    {
        DB::beginTransaction();
        try{
            $datas = $request->validate([
                'description' => 'nullable|string',
                'image' => 'nullable|image|file|max:2048|mimes:jpeg,png,jpg',
                'link' => 'nullable|string',
            ]);

            $update = $this->electionCandidates->findOrfail($id);
            $datas['link'] = $this->helper->replaceDomain('base_url_youtube', 'base_url_youtube_web', $datas['link']);
            $update->update($datas);
            $folder = 'dgolf/social-media/election/candidates';
            $column = 'image';
            $this->helper->uploads($folder, $update, $column);
            DB::commit();
            return $this->web->updateReturn('elections.edit', 'election', $update->t_election_id);
        } catch (\Throwable $e) {
            DB::rollBack();
            if($e instanceof ValidationException){
                return $this->web->error_validation($e);
            }
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function destroyCandidate(string $id)
    {
        DB::beginTransaction();
        try{
            $destroy = $this->electionCandidates->findOrfail($id);
            $folder = 'dgolf/social-media/election/candidates';
            $column = 'image';
            $this->helper->deleteUploads($folder, $destroy, $column);
            $destroy->delete();
            DB::commit();
            return $this->web->successReturn('elections.edit', 'election', $destroy->t_election_id, 'Berhasil Menghapus Data');
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function destroyPersonResponsible(string $id)
    {
        DB::beginTransaction();
        try{
            $destroy = $this->electionPersonResponsible->findOrfail($id);
            $destroy->delete();
            DB::commit();
            return $this->web->successReturn('elections.edit', 'election', $destroy->t_election_id, 'Berhasil Menghapus Data');
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function resultsCandidate(string $id)
    {
        DB::beginTransaction();
        try{
            $election = $this->model->with(['candidates' => function ($query) {
                $query->with(['user:id,name'])->withCount('voters');
            }])->where(function ($q) {
                $q->where('is_opend', true)->orWhere('end_date', '>', now());
            })->findOrFail($id);
            $election->candidates = $election->candidates->sortByDesc('voters_count')->values();

            $data = [
                'content' => 'Admin/SocialMedia/Election/results',
                'title' => 'Result Election',
                'election' => $election
            ];
            return view('Admin.Layouts.wrapper', $data);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->handler->handleExceptionWeb($e);
        }
    }
}
