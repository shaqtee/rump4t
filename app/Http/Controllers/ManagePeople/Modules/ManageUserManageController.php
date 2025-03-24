<?php

namespace App\Http\Controllers\ManagePeople\Modules;

use Carbon\Carbon;
use App\Models\User;
use App\Exceptions\Handler;
use Illuminate\Http\Request;
use App\Services\WebRedirect;
use Illuminate\Validation\Rule;
use App\Services\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Masters\App\Models\MasterCity;
use Illuminate\Validation\ValidationException;
use Modules\Performace\App\Models\ScoreHandicap;
use Modules\Masters\App\Models\MasterConfiguration;

class ManageUserManageController extends Controller
{
    protected $model;
    protected $helper;
    protected $handler;
    protected $web;
    protected $config;
    protected $city;
    protected $sh;

    public function __construct(User $model, Helper $helper, Handler $handler, WebRedirect $web, MasterConfiguration $config, MasterCity $city, ScoreHandicap $sh)
    {
        $this->model = $model;
        $this->helper = $helper;
        $this->handler = $handler;
        $this->web = $web;
        $this->config = $config;
        $this->city = $city;
        $this->sh = $sh;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try{
            $page = $request->size ?? 10;
            $data = [
                'content' => 'ManagePeople/Users/index',
                'title' => 'Data User',
                'users' => $this->model->with(['community:id,title', 'group:id,name', 'city:id,name'])->where('t_community_id', auth()->user()->t_community_id)->orderByDesc('id')->paginate($page),
            ];

            return view('ManagePeople.Layouts.wrapper', $data);

        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try{
            $now = Carbon::now()->year;
            $yearAgo = 100;
            $dataYears = [];

            for ($i = 0; $i <= $yearAgo; $i++) { 
                $dataYears[] = $now - $i;
            }

            $data = [
                'content' => 'ManagePeople/Users/addEdit',
                'title' => 'Create Data User',
                'users' => null,
                'faculty' => $this->config->where('parameter', 'm_faculty')->get(),
                'years' => $dataYears,
                'city' => $this->city->get(),
            ];

            return view('ManagePeople.Layouts.wrapper', $data);

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
            $datas = $request->validate([
                'name' => 'required|string',
                'image' => 'required|image|file|max:2048|mimes:jpeg,png,jpg',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('users')->ignore($request->user()->id),
                ],
                'phone' => [
                    'required',
                    'string',
                    Rule::unique('users')->ignore($request->user()->id),
                ],
                'gender' => 'required|in:L,P',
                'birth_date' => 'required',
                'hcp_index' => 'required|numeric',
                'faculty' => 'required|string',
                'batch' => 'required',
                'office_name' => 'required|string',
                'address' => 'required|string',
                't_city_id' => 'required',
                'business_sector' => 'required|string',
                'position' => 'required|string',
                'is_admin' => 'nullable',
                'active' => 'required|in:1,0',
            ]);
            $datas['t_community_id'] = auth()->user()->t_community_id;

            $folder = "dgolf/user-profile";
            $column = "image";

            $datas['flag_done_profile'] = '1';
            $datas['email_verified_at'] = now();
            $datas['phone_verified_at'] = now();
            $datas['password'] = bcrypt(123123);

            $model = $this->model->create($datas);

            $this->helper->uploads($folder, $model, $column);

            DB::commit();
            return $this->web->store('users.manage.semua');
        } catch (\Throwable $e) {
            DB::rollBack();
            if($e instanceof ValidationException){
                return $this->web->error_validation($e);
            }
            return $this->handler->handleExceptionWeb($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $data = [
                'content' => 'ManagePeople/Users/show',
                'title' => 'Update Data User',
                'users' => $this->model->with(['city:id,name'])->findOrfail($id),
            ];

            return view('ManagePeople.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try{
            $now = Carbon::now()->year;
            $yearAgo = 100;
            $dataYears = [];

            for ($i = 0; $i <= $yearAgo; $i++) { 
                $dataYears[] = $now - $i;
            }

            $data = [
                'content' => 'ManagePeople/Users/addEdit',
                'title' => 'Update Data User',
                'users' => $this->model->findOrfail($id),
                'faculty' => $this->config->where('parameter', 'm_faculty')->get(),
                'years' => $dataYears,
                'city' => $this->city->get(),
            ];

            return view('ManagePeople.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();
        try{
            $datas = $request->validate([
                'name' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg',
                // 'email' => [
                //     'required',
                //     'email',
                //     Rule::unique('users')->ignore($request->user()->id),
                // ],
                // 'phone' => [
                //     'required',
                //     'string',
                //     Rule::unique('users')->ignore($request->user()->id),
                // ],
                'gender' => 'nullable|in:L,P',
                'birth_date' => 'required',
                'hcp_index' => 'required|numeric',
                'faculty' => 'nullable|string',
                'batch' => 'nullable',
                'office_name' => 'required|string',
                'address' => 'required|string',
                // 't_city_id' => 'nullable',
                'business_sector' => 'required|string',
                'position' => 'required|string',
                'is_admin' => 'nullable',
                'active' => 'nullable|in:1,0',
            ]);
            $datas['t_community_id'] = auth()->user()->t_community_id;

            $folder = "dgolf/user-profile";
            $column = "image";

            $model = $this->model->findOrfail($id);

            $model->update($datas);

            $this->helper->uploads($folder, $model, $column);

            DB::commit();
            return $this->web->update('users.manage.semua');
        } catch (\Throwable $e) {
            DB::rollBack();
            if($e instanceof ValidationException){
                return $this->web->error_validation($e);
            }
            return $this->handler->handleExceptionWeb($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try{
            $this->model->findOrfail($id)->delete();

            DB::commit();
            return $this->web->destroy('users.manage.semua');
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function game_score(string $id)
    {
        try{
            $data = [
                'content' => 'ManagePeople/Users/gameScore',
                'title' => 'Data Game Score User',
                'users' => $this->model->with([
                            'MyScore' => function($q) {
                                $q->with(['event:id,title', 'letsPlay:id,title']);
                            }
                        ])->findOrfail($id),
            ];

            return view('ManagePeople.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function hcp_index(string $id)
    {
        try{
            $data = [
                'content' => 'ManagePeople/Users/hcpIndex',
                'title' => 'Data Handicap Index User',
                'users' => $this->model->findOrfail($id),
                'hcp' => $this->hcp($id),
            ];
            return view('ManagePeople.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function hcp($id){
        try {
            $datas = $this->sh->where('t_user_id', $id)
                            ->with([
                                'golfCourse' => function($q) {
                                    $q->select('id', 'name', 'course_rating', 'slope_rating');
                                }
                            ])
                            ->get();

            if($datas->count() != 0 || $datas->count() != null) {
                $minTotRounds = 5;
                $hdConst = 113; // menghitung handicap diff
                $hdConst2 = 0.96; // menghitung handicap index
                $totRounds = $datas->count(); // Rounds
                $sumScore = $datas->sum('gross_score');
                $ags = floor(($sumScore / $totRounds) * 10) / 10; //Adjusted Gross Score
                $handicapDiff = collect();
                foreach($datas as $d) {
                    // $nilai = ($ags - round($d->golfCourse->course_rating, 1)) * $hdConst / intval($d->golfCourse->slope_rating);
                    $nilai = ($ags - round($d->course_rating, 1)) * $hdConst / intval($d->slope_rating);
                    $handicapDiff->push($nilai);
                }
    
                $nilaiSortDesc = $handicapDiff->sort();
                $totNilaiHandicapDiff = null; //total nilai hadicap yg diambil
                if($totRounds == 5){
                    $totNilaiHandicapDiff = 1;
                } else if($totRounds == 8) {
                    $totNilaiHandicapDiff = 2;
                } else if($totRounds == 10) {
                    $totNilaiHandicapDiff = 3;
                } else if($totRounds == 20) {
                    $totNilaiHandicapDiff = 10;
                } else if($totRounds >= 20) {
                    $totNilaiHandicapDiff = 10;
                }
    
                $handicapDiffTerbaik = array_slice($nilaiSortDesc->toArray(), 0, $totNilaiHandicapDiff);
                $handicapDiffTerbaik = floor($handicapDiffTerbaik[0] * 10) / 10;
                $handicapIndex = floor(($handicapDiffTerbaik * $hdConst2) * 10) / 10;
                // $handicapIndex = round(round($handicapDiffTerbaik[0], 1) * $hdConst2, 1);

    
                if($totRounds < $minTotRounds){
                    $handicapIndex = "N/A";
                }
    
                $datas = [
                    'avgScore' => $ags,
                    'rounds' => $totRounds,
                    'handicapIndex' => $handicapIndex,
                ];
            } else {
                $datas = [
                    'avgScore' => null,
                    'rounds' => null,
                    'handicapIndex' => "N/A",
                ];
            }

            return $datas;
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function index_admin(Request $request)
    {
        try{
            $page = $request->size ?? 10;
            $data = [
                'content' => 'ManagePeople/Users/Admin/index',
                'title' => 'Data User Admin',
                'admin' => $this->model->with(['community:id,title', 'group:id,name', 'city:id,name'])->where('is_admin', 1)->orderByDesc('id')->paginate($page),
                'users' => $this->model->where('flag_done_profile', 1)->where('is_admin', 0)->orWhere('is_admin', null)->get(),
            ];

            return view('ManagePeople.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }
}
