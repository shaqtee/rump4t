<?php

namespace Modules\Polling\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Polling;
use App\Models\PollingOption;
use App\Models\PollingVote;
use App\Services\ApiResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PollingController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $api;
    protected $polling;
    protected $option;
    protected $vote;
    
    public function __construct(ApiResponse $api, Polling $polling, PollingOption $option, PollingVote $vote)
    {
        $this->api = $api;
        $this->polling = $polling;
        $this->option = $option;
        $this->vote = $vote;
    }

    public function index(Request $request)
    {
        try {
            $status = $request->query('status'); 
            $id = $request->query('id'); 
    
            if ($id) {
                // Detail polling
                $polling = Polling::with(['options.votes'])->findOrFail($id);
                $totalVotes = $polling->options->sum(fn($opt) => $opt->votes->count());
    
                $options = $polling->options->map(fn($option) => [
                    'id' => $option->id,
                    'text' => $option->option_text,
                    'image' => $option->option_image,
                    'votes_count' => $option->votes->count(),
                ]);
    
                $data = [
                    'id' => $polling->id,
                    'title' => $polling->title,
                    'title_description' => $polling->title_description,
                    'question' => $polling->question,
                    'question_description' => $polling->question_description,
                    'is_active' => $polling->is_active,
                    'deadline' => $polling->deadline,
                    'created_at' => $polling->created_at,
                    'total_votes' => $totalVotes,
                    'options' => $options,
                ];
    
                return $this->api->list($data, $this->polling);
            }
            // List polling
            $query = Polling::with(['options.votes'])->orderBy('created_at', 'desc');

            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
    
            $pollings = $query->get()->map(function ($polling) {
                $totalVotes = $polling->options->sum(fn($opt) => $opt->votes->count());
    
                $options = $polling->options->map(fn($option) => [
                    'id' => $option->id,
                    'text' => $option->option_text,
                    'image' => $option->option_image,
                    'votes_count' => $option->votes->count(),
                ]);
    
                return [
                    'id' => $polling->id,
                    'title' => $polling->title,
                    'title_description' => $polling->title_description,
                    'question' => $polling->question,
                    'question_description' => $polling->question_description,
                    'is_active' => $polling->is_active,
                    'deadline' => $polling->deadline,
                    'created_at' => $polling->created_at,
                    'total_votes' => $totalVotes,
                    'options' => $options,
                ];
            });    

            return $this->api->list($pollings, $this->polling);
        } catch (\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }


    public function submit_vote(Request $request)
    {
        try {
            $request->validate([
                'polling_option_id' => 'required|exists:t_polling_options,id',
                'note' => 'nullable|string|max:500'
            ]);

            $userId = Auth::id(); 
            dd($userId);

            $option = $this->option->findOrFail($request->polling_option_id);
            $pollingId = $option->polling_id;

            $alreadyVoted = $this->vote
                ->where('user_id', $userId)
                ->whereHas('option', fn($q) => $q->where('polling_id', $pollingId))
                ->exists();

            if ($alreadyVoted) {
                return $this->api->error("You have already voted in this poll.");
            }

            $this->vote->create([
                'polling_id' => $pollingId,
                'polling_option_id' => $request->polling_option_id,
                'user_id' => $userId,
                'note' => $request->note
            ]);

            return $this->api->success("Vote submitted successfully.");

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
        return view('polling::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('polling::edit');
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
