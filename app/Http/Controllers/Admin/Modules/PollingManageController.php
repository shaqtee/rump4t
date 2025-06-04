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
use App\Models\Polling;
use App\Models\PollingOption;
use App\Models\PollingVote;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Modules\Community\App\Models\Community;
use Modules\Regions\App\Models\Region;

class PollingManageController extends Controller
{
    protected $model;
    protected $helper;
    protected $handler;
    protected $web;
    protected $option;
    protected $vote;

    public function __construct(Polling $model, Helper $helper, Handler $handler, WebRedirect $web, PollingOption $option, PollingVote $vote)
    {
        $this->model = $model;
        $this->helper = $helper;
        $this->handler = $handler;
        $this->web = $web;
        $this->option = $option;
        $this->vote = $vote;
    }
    /**
     * Display a listing of the resource.
     */
    public function index_admin(Request $request)
    {
        try{
            $page = $request->size ?? 10;
            $data = [
                'content' => 'Admin/Polling/index',
                'title' => 'Data Polling',
                'pollings' => $this->model->with(['options', 'votes', 'user'])
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
                'content' => 'Admin/Polling/addEdit',
                'title' => 'Add Data Polling',
                'pollings' => null,
                'regions' => Region::where('parameter', 'm_region')->get(),
                'communities' => Community::all(),
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

            $polling = Polling::create([
                'title' => $validated['title'],
                'title_description' => $validated['title_description'] ?? null,
                'question' => $validated['question'],
                'question_description' => $validated['question_description'] ?? null,
                'is_active' => true,
                'start_date' => $validated['start_date'] ?? null,
                'deadline' => $validated['deadline'] ?? null,
                'target_roles' => $validated['target_roles']
                    ? '{' . implode(',', $validated['target_roles']) . '}'
                    : null,
                'target_region_id' => $validated['target_region_id'] ?? null,
                'target_community_id' => $validated['target_community_id'] ?? null,
                'created_by' => auth()->id(),
                'created_at' => now(),
            ]);

            DB::commit();
            return $this->web->store('polling.admin');

        } catch (\Throwable $e) {
            DB::rollBack();
            if($e instanceof ValidationException){
                return $this->web->error_validation($e);
            }
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function create_option($id)
    {
        try{
            $options = PollingOption::where('polling_id', $id)->get();

            $data = [
                'content' => 'Admin/Polling/addEdit_Option',
                'title' => 'Add Polling Option Data',
                'polling_id' => $id,
                'options' => $options,
            ];
            return view('Admin.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function store_option(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
    
        try {
            $request->validate([
                'polling_id' => 'required|exists:t_pollings,id',
                'option_value.*' => 'nullable|string|max:10',
                'option_text.*' => 'nullable|string',
                'option_image.*' => 'nullable|image|max:2048',
            ]);
    
            $optionIds = $request->option_id ?? [];
            $existingOptionIds = $this->option
                ->where('polling_id', $request->polling_id)
                ->pluck('id')
                ->toArray();

            $processedIds = [];

            foreach ($request->option_text as $index => $text) {
                $optionId = $optionIds[$index] ?? null;
                $value = $request->option_value[$index] ?? null;
                $file = $request->file('option_image')[$index] ?? null;

                if ($optionId && in_array($optionId, $existingOptionIds)) {
                    // Update
                    $option = $this->option->find($optionId);
                    $option->option_value = $value;
                    $option->option_text = $text;
    
                    if ($file && $file->isValid()) {
                        $path = $file->store('rump4t/polling/polling-images', 's3');
                        $url = Storage::disk('s3')->url($path);
                
                        $option->option_image = $url;
                    }
    
                    $option->save();
                    $processedIds[] = $optionId;
    
                } else {
                    // Tambah baru
                    $newOption = $this->option->create([
                        'polling_id' => $request->polling_id,
                        'option_value' => $value,
                        'option_text' => $text,
                    ]);
    
                    if ($file && $file->isValid()) {
                        $path = $file->store('rump4t/polling/polling-images', 's3');
                        $url = Storage::disk('s3')->url($path);
                
                        $newOption->update([
                            'option_image' => $url,
                        ]);                    
                    }
    
                    $processedIds[] = $newOption->id;
                }
            }

            // Hapus opsi yang dihapus
            $toDelete = array_diff($existingOptionIds, $processedIds);
            if (!empty($toDelete)) {
                $this->option->whereIn('id', $toDelete)->delete();
            }
    
            DB::commit();
            return $this->web->store('polling.admin');
    
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
