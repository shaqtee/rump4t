<?php

namespace App\Http\Controllers\Admin\Modules\Masters;
use App\Exceptions\Handler;
use Illuminate\Http\Request;
use App\Services\WebRedirect;
use App\Services\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Modules\Masters\App\Models\MasterRules;
use Modules\Masters\App\Models\MasterRulesDetail;

class MasterRulesScoreController extends Controller
{
    protected $model;
    protected $web;
    protected $handler;
    protected $helper;
    protected $detailRules;

    public function __construct(MasterRules $model, WebRedirect $web, Handler $handler, Helper $helper, MasterRulesDetail $detailRules)
    {
        $this->model = $model;
        $this->web = $web;
        $this->handler = $handler;
        $this->helper = $helper;
        $this->detailRules = $detailRules;
    }

     /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try{
            $page = $request->size ?? 10;
            $rulesScore = $this->model->with('details')->filter($request)->paginate($page)->appends($request->all());

            foreach ($rulesScore as $rule) {
                $holesData = $rule->details->pluck('holes')->toArray(); 
                $rule->holes = implode(',', $holesData); 
            }
            
            $data = [
                'content' => 'Admin/Masters/Rules/index',
                'title' => 'Data Rules Score',
                'rulesScore' => $rulesScore,
                'columns' => $this->model->columns(),
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
                'content' => 'Admin/Masters/Rules/addEdit',
                'title' => 'Add Rules Score',
                'rulesScore' => null,
            ];
            return view('Admin.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            $validatedData = $request->validate([
                'name' => 'required',
                'holes' => 'required|array|min:1',
                'holes.*' => 'integer',
            ]);

            $rule = $this->model->create([
                'name' => $request->name,
            ]);

            $holesData = array_map(function ($hole) use ($rule) {
                return [
                    'id_rules' => $rule->id,
                    'holes' => $hole,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }, $validatedData['holes']);

            MasterRulesDetail::insert($holesData);

            DB::commit();
            return $this->web->store('rules-score.index');
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
        $rulesScore = $this->model->with('details')->findOrFail($id);
        $rulesScore->holes = $rulesScore->details->pluck('holes')->toArray();

        try{
            $data = [
                'content' => 'Admin/Masters/Rules/addEdit',
                'title' => 'Edit Rules Score',
                'rulesScore'=> $rulesScore,
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
                'name' => 'required',
                'holes' => 'required|array|min:1',
                'holes.*' => 'integer',
            ]);

            $rule = $this->model->findOrFail($id);

            $rule->update([
                'name' => $datas['name'],
            ]);
    
            MasterRulesDetail::where('id_rules', $rule->id)->delete();
    
            $holesData = array_map(function ($hole) use ($rule) {
                return [
                    'id_rules' => $rule->id,
                    'holes' => $hole,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }, $datas['holes']);
    
            MasterRulesDetail::insert($holesData);
    
            DB::commit();
            return $this->web->update('rules-score.index');
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

            $rule = $this->model->findOrFail($id);

            MasterRulesDetail::where('id_rules', $rule->id)->delete();
            $rule->delete();

            DB::commit();
            return $this->web->destroy('rules-score.index');
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->handler->handleExceptionWeb($e);
        }
    }



}
