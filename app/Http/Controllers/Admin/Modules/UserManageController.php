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
use Illuminate\Support\Facades\Auth;
use Modules\Masters\App\Models\MasterCity;
use Modules\Masters\App\Models\MasterVillage;
use Modules\Masters\App\Models\MasterDistrict;
use Modules\Masters\App\Models\MasterRegency;
use Modules\Masters\App\Models\MasterProvince;
use Modules\Masters\App\Models\MasterReferences;
use Illuminate\Validation\ValidationException;
use Modules\Community\App\Models\Community;
use Modules\Community\App\Models\MembersCommonity;
use Modules\Performace\App\Models\ScoreHandicap;
use Modules\Masters\App\Models\MasterConfiguration;
use Modules\Performace\App\Http\Controllers\PerformaceController;

class UserManageController extends Controller
{
    protected $model;
    protected $helper;
    protected $handler;
    protected $web;
    protected $config;
    protected $city;
    protected $village;
    protected $district;
    protected $regency;
    protected $province;
    protected $references;
    protected $sh;
    protected $community;
    protected $memberComm;

    public function __construct(User $model, Helper $helper, Handler $handler, WebRedirect $web, MasterConfiguration $config, MasterCity $city, MasterVillage $village, MasterDistrict $district, MasterRegency $regency, MasterProvince $province, MasterReferences $references, ScoreHandicap $sh, Community $community, MembersCommonity $memberComm)
    {
        $this->model = $model;
        $this->helper = $helper;
        $this->handler = $handler;
        $this->web = $web;
        $this->config = $config;
        $this->city = $city;
        $this->village = $village;
        $this->district = $district;
        $this->regency = $regency;
        $this->province = $province;
        $this->references = $references;
        $this->sh = $sh;
        $this->community = $community;
        $this->memberComm = $memberComm;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try{
            $page = $request->size ?? 10;
            $data = [
                'content' => 'Admin/Users/index',
                'title' => 'Data User',
                'users' => $this->model->with(['community:id,title', 'group:id,name', 'city:id,name'])->filter($request)->orderByDesc('id')->paginate($page)->appends($request->all()),
                'columns' => $this->model->columnsWeb(),
            ];

            return view('Admin.Layouts.wrapper', $data);

        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }
    public function reset_password(Request $request, string $id)
    {
        DB::beginTransaction();
        try{
  

            $model = $this->model->findOrfail($id);

            $model->update([
                'password' => "rump4t123",
                "reset_request" => false,
            ]);

            DB::commit();
            return $this->web->update('users.semua');
        } catch (\Throwable $e) {
            DB::rollBack();
            if($e instanceof ValidationException){
                return $this->web->error_validation($e);
            }
            return $this->handler->handleExceptionWeb($e);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // dd($this->references->where('parameter', 'm_region')->get());
        // dd($this->community->get());
        try{
            $now = Carbon::now()->year;
            $yearAgo = 100;
            $dataYears = [];

            for ($i = 0; $i <= $yearAgo; $i++) { 
                $dataYears[] = $now - $i;
            }

            $data = [
                'content' => 'Admin/Users/addEdit',
                'title' => 'Registrasi Data Anggota',
                'users' => null,
                'community' => $this->community->get(),
                'faculty' => $this->config->where('parameter', 'm_faculty')->get(),
                'years' => $dataYears,
                'city' => $this->city->get(),
                'villages' => $this->village->get(),
                'districts' => $this->district->get(),
                'regencies' => $this->regency->get(),
                'provinces' => $this->province->get(),
                'regions' => $this->references->where('parameter', 'm_region')->get(),
                'retirement_type' => $this->references->where('parameter', 'm_retirement_type')->get(),
                'last_employee_status' => $this->references->where('parameter', 'm_last_employee_status')->get(),
                'shirt_size' => $this->references->where('parameter', 'm_shirt_size')->get(),
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
            $datas = $request->validate([
                'name' => 'required|string',
                'nickname' => 'nullable|string',
                'image' => 'nullable|image|file|max:2048|mimes:jpeg,png,jpg',
                'email' => [
                    'nullable',
                    // 'required',
                    'email',
                    Rule::unique('users'),
                ],
                'phone' => [
                    'required',
                    'string',
                    Rule::unique('users'),
                ],
                'gender' => 'nullable|in:L,P',
                'birth_place' => 'nullable|string',
                'birth_date' => 'nullable',
                'age' => 'nullable|numeric',
                'address' => 'nullable|string',
                'desa_kelurahan' => 'nullable',
                'kecamatan' => 'nullable',
                'kota_kabupaten' => 'nullable',
                'provinsi' => 'nullable',
                'region' => 'required|numeric',
                'postal_code' => 'nullable|numeric',
                'year_of_entry' => 'nullable|numeric',
                'year_of_retirement' => 'nullable|numeric',
                'retirement_type' => 'nullable|numeric',
                'last_employee_status' => 'nullable|numeric',
                'position' => 'nullable|string',
                'last_division' => 'nullable|string',
                'spouse_name' => 'nullable|string',
                'shirt_size' => 'nullable|numeric',
                'notes' => 'nullable|string',
                'ec_name' => 'nullable|string',
                'ec_kinship' => 'nullable|string',
                'ec_contact' => 'nullable|string',
                't_community_id' => 'nullable',
                'pass_away_status' => 'nullable',
                'status_anggota' => 'required|in:1,2',
                'active' => 'required|in:1,0',
            ]);
            
            $folder = "rump4t/user-profile";
            $column = "image";

            $datas['pass_away_status'] = empty($datas['pass_away_status']) ? false : true;
            $datas['status_anggota'] = empty($datas['status_anggota']) ? 1 : $datas['status_anggota'];
            $datas['flag_done_profile'] = '1';
            $datas['email_verified_at'] = now();
            $datas['phone_verified_at'] = now();
            $datas['password'] = bcrypt(123123);
            // dd($datas);
            $model = $this->model->create($datas);

            $this->helper->uploads($folder, $model, $column);

            /* create memberComm */
            $createMember = [
                't_user_id' => $model->id,
                't_community_id' => $datas['t_community_id'],
                'active' => 1,
            ];
            
            /* nomor anggota */
            $region = $this->references->where('id', $model->region)->first();
            $digits = abs((int)$model->id);
            $nomor_anggota = str_pad($digits, 3, '0', STR_PAD_LEFT).'-'.$region->code.'-'.$model->status_anggota;

            if(!empty($datas['t_community_id'])){
                /* join community */
                $updateUser = [
                    'flag_community' => 'JOINED',
                    'nomor_anggota' => $nomor_anggota,
                ];
                
                $model->update($updateUser);
                $this->memberComm->create($createMember);
            }else{
                $updateUser = ['nomor_anggota' => $nomor_anggota];
                $model->update($updateUser);
            }

            DB::commit();
            return $this->web->store('users.semua');
        } catch (\Throwable $e) {
            report($e);
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
            $users = $this->model->with(['city:id,name'])->findOrfail($id);
            $data = [
                'content' => 'Admin/Users/show',
                'title' => 'Show Data User',
                'users' => $users,
                'region' => $this->references->where('id', $users->region)->first(),
                'retirement_type' => $this->references->where('id', $users->retirement_type)->first(),
                'last_employee_status' => $this->references->where('id', $users->last_employee_status)->first(),
                'shirt_size' => $this->references->where('id', $users->shirt_size)->first()
            ];

            return view('Admin.Layouts.wrapper', $data);
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
                'content' => 'Admin/Users/addEdit',
                'title' => 'Update Data User',
                'users' => $this->model->findOrfail($id),
                'community' => $this->community->get(),
                'faculty' => $this->config->where('parameter', 'm_faculty')->get(),
                'years' => $dataYears,
                'city' => $this->city->get(),
                'villages' => $this->village->get(),
                'districts' => $this->district->get(),
                'regencies' => $this->regency->get(),
                'provinces' => $this->province->get(),
                'regions' => $this->references->where('parameter', 'm_region')->get(),
                'retirement_type' => $this->references->where('parameter', 'm_retirement_type')->get(),
                'last_employee_status' => $this->references->where('parameter', 'm_last_employee_status')->get(),
                'shirt_size' => $this->references->where('parameter', 'm_shirt_size')->get(),
            ];

            return view('Admin.Layouts.wrapper', $data);
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
            $model = $this->model->findOrfail($id);
            
            $datas = $request->validate([
                'name' => 'required|string',
                'nickname' => 'nullable|string',
                'image' => 'nullable|image|file|max:2048|mimes:jpeg,png,jpg',
                'email' => [
                    'nullable',
                    // 'required',
                    'email',
                    Rule::unique('users')->ignore($model->id),
                ],
                'phone' => [
                    'required',
                    'string',
                    Rule::unique('users')->ignore($model->id),
                ],
                'gender' => 'nullable|in:L,P',
                'birth_place' => 'nullable|string',
                'birth_date' => 'nullable',
                'age' => 'nullable|numeric',
                'address' => 'nullable|string',
                'desa_kelurahan' => 'nullable',
                'kecamatan' => 'nullable',
                'kota_kabupaten' => 'nullable',
                'provinsi' => 'nullable',
                'region' => 'required|numeric',
                'postal_code' => 'nullable|numeric',
                'year_of_entry' => 'nullable|numeric',
                'year_of_retirement' => 'nullable|numeric',
                'retirement_type' => 'nullable|numeric',
                'last_employee_status' => 'nullable|numeric',
                'position' => 'nullable|string',
                'last_division' => 'nullable|string',
                'spouse_name' => 'nullable|string',
                'shirt_size' => 'nullable|numeric',
                'notes' => 'nullable|string',
                'ec_name' => 'nullable|string',
                'ec_kinship' => 'nullable|string',
                'ec_contact' => 'nullable|string',
                't_community_id' => 'nullable',
                'pass_away_status' => 'nullable',
                'status_anggota' => 'required|in:1,2',
                'active' => 'required|in:1,0',
            ]);
            // dd($datas);
            $folder = "rump4t/user-profile";
            $column = "image";
            
            if (!empty($model->t_community_id) && !empty($datas['t_community_id'])) {
                $modelMember = $this->memberComm->where('t_user_id', $model->id)->where('t_community_id', $model->t_community_id)->first();

                /* change community */
                if( !empty($modelMember) ){
                    $modelMember->update([
                        't_community_id' => $datas['t_community_id'],
                    ]);
                }else{
                    /* user not registered comm but have t_community_id */
                    $createMember = [
                        't_user_id' => $id,
                        't_community_id' => $datas['t_community_id'],
                        'active' => 1,
                    ];
                    $updateUser = [
                        'flag_community' => 'JOINED',
                    ];
                    $model->update($updateUser);
                    $modelMember = $this->memberComm->create($createMember);
                }
            }
            
            /* haven't join at all */
            if (!empty($datas['t_community_id']) && !isset($model->t_community_id)) {
                
                $createMember = [
                    't_user_id' => $id,
                    't_community_id' => $datas['t_community_id'],
                    'active' => 1,
                ];
                $updateUser = [
                    'flag_community' => 'JOINED',
                ];
                $model->update($updateUser);
                $modelMember = $this->memberComm->create($createMember);
            }

            $region = $this->references->where('id', $datas['region'])->first();
            $digits = abs((int)$model->id);
            $nomor_anggota = str_pad($digits, 3, '0', STR_PAD_LEFT).'-'.$region->code.'-'.$datas['status_anggota'];

            $datas['nomor_anggota'] = $nomor_anggota;
            $model->update($datas);

            $this->helper->uploads($folder, $model, $column);

            DB::commit();
            return $this->web->update('users.semua');
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
            return $this->web->destroy('users.semua');
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function game_score(string $id)
    {
        try{
            $data = [
                'content' => 'Admin/Users/gameScore',
                'title' => 'Data Game Score User',
                'users' => $this->model->with([
                            'MyScore' => function($q) {
                                $q->with(['event:id,title', 'letsPlay:id,title']);
                            }
                        ])->findOrfail($id),
            ];

            return view('Admin.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function hcp_index(string $id)
    {
        try{
            $data = [
                'content' => 'Admin/Users/hcpIndex',
                'title' => 'Data Handicap Index User',
                'users' => $this->model->findOrfail($id),
                'hcp' => $this->hcp($id),
            ];
            return view('Admin.Layouts.wrapper', $data);
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
                'content' => 'Admin/Users/Admin/index',
                'title' => 'Data User Admin',
                'admin' => $this->model->with(['community:id,title', 'group:id,name', 'city:id,name'])->where('is_admin', 1)->filter($request)->orderByDesc('id')->paginate($page)->appends($request->all()),
                'users' => $this->model->where('flag_done_profile', 1)->where('is_admin', 0)->orWhere('is_admin', null)->get(),
                'columns' => $this->model->columnsWeb(),
            ];

            return view('Admin.Layouts.wrapper', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function store_admin(Request $request)
    {
        DB::beginTransaction();
        try{
            $datas = $request->validate([
                'is_admin' => 'required',
                't_group_id' => 'required',
                'email' => 'required',
            ]);
            
            $model = $this->model->findOrfail($request->id);

            $model->update($datas);
            
            DB::commit();
            return $this->web->update('users.admin.semua');
        } catch (\Throwable $e) {
            DB::rollBack();
            if($e instanceof ValidationException){
                return $this->web->error_validation($e);
            }
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function update_admin(Request $request, string $id)
    {
        DB::beginTransaction();
        try{
            $datas = $request->validate([
                'is_admin' => 'required',
                't_group_id' => 'required',
            ]);

            $model = $this->model->findOrfail($id);
            $model->is_admin = NULL;
            $model->t_group_id = NULL;
            $model->save();
            
            DB::commit();
            if(Auth::user()->id == $id && $datas['is_admin'] == 0) {
                Auth::logout();
                return redirect('');
            }
            return $this->web->update('users.admin.semua');
        } catch (\Throwable $e) {
            DB::rollBack();
            if($e instanceof ValidationException){
                return $this->web->error_validation($e);
            }
            return $this->handler->handleExceptionWeb($e);
        }
    }

    public function delete_soft($id)
    {
        $user = $this->model->find($id);
        $user->delete();

        return redirect('/admin/users/index');
    }

    public function lists(Request $request, $scope)
    {
        $data = $request->all();
        $lists = $this->$scope->with($data['relation'])->where('id', $data['id'])->first();
        return response()->json([
            "status" => "success",
            "rsp" => $lists,
        ],200);
    }

    public function user_by_id($id)
    {
        $user = $this->model->where('id',$id)->first();
        
        return response()->json([
            "status" => "success",
            "rsp" => $user,
        ],200);
    }
}
