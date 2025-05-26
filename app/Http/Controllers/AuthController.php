<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Otp;
use App\Models\User;
use App\Mail\LoginMail;
use Exception;
use Hash;
use Twilio\Rest\Client;
use App\Mail\GreetingMail;
use App\Mail\ResendOtpMail;
use Illuminate\Http\Request;
use App\Services\ApiResponse;
use App\Models\CompanyProfile;
use App\Mail\VerivicationEmail;
use App\Services\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\RegisRequest;
use App\Http\Requests\verifRequest;
use App\Mail\VerificationUpdateMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Services\Interfaces\UserInterface;
use Modules\Community\App\Models\Community;
use Modules\Community\App\Models\MemberEvent;
use Modules\Community\App\Models\MembersCommonity;
use Modules\Masters\App\Models\MasterConfiguration;
use Modules\Masters\App\Models\MasterReferences;
use Modules\Masters\App\Models\MasterCity;
use Modules\Masters\App\Models\MasterRegency;
use Modules\MyGames\App\Models\MemberLetsPlay;
use Modules\Performace\App\Http\Controllers\PerformaceController;
use Modules\ScoreHandicap\App\Models\ScoreHandicap;

class AuthController extends Controller
{
    protected $model;
    protected $api;
    protected $helper;
    protected $interface;
    protected $config;
    protected $companyProfile;
    protected $community;
    protected $memberCommunity;
    protected $performanceController;
    protected $references;
    protected $city;
    protected $regency;

    public function __construct(User $model, ApiResponse $api, Helper $helper, UserInterface $interface, MasterConfiguration $config, CompanyProfile $companyProfile, Community $community, MembersCommonity $memberCommunity, PerformaceController $performanceController, MasterReferences $references, MasterCity $city, MasterRegency $regency)
    {
        $this->model = $model;
        $this->api = $api;
        $this->helper = $helper;
        $this->interface = $interface;
        $this->config = $config;
        $this->companyProfile = $companyProfile;
        $this->community = $community;
        $this->memberCommunity = $memberCommunity;
        $this->performanceController = $performanceController;
        $this->references = $references;
        $this->city = $city;
        $this->regency = $regency;
    }

    //register
    public function get_user2(Request $request){ //Tombol Register Hal-5 dan Hal-8
        try {
            $users = $this->model;

            if($request->phone){
                $user = $users->select('phone')->where('phone', $request->phone)->first();

                if(!$user){
                    return  $this->api->error("Phone Number Not Found!");
                }
            } else {
                $user = $users->select('email')->where('email', $request->email)->first();

                if(!$user){
                    return  $this->api->error("Email Not Found!");
                }
            }

            return $this->api->success($user);
        } catch(\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    // register sync this
    public  function registrasi(RegisRequest $request){ //Tombol Send Verification Code Hal-6
        DB::beginTransaction();
        try {
            $datas = $request->validated();
            
            $userCheckPhone = $this->model->where('phone', $request->phone)->first();
            $userCheckEmail = $this->model->where('email', $request->email)->first();
            
            if($userCheckPhone && $userCheckPhone->flag_done_profile != '1'){
                $userCheckPhone->delete();
            }
            
            if($userCheckPhone && $userCheckPhone->phone == $request->phone){
                return  $this->api->error("Phone Number Has Already Been Registered");
            }
            
            if(!empty($request->email) && $userCheckEmail?->email == $request->email){
                return  $this->api->error("Email Has Already Been Registered");
            }
            
            // if numbers starts with 0. change to 62
            if(substr($request->phone, 0, 1) == '0'){
                $datas['phone'] = '62'.substr($request->phone, 1);
                
                $userCheckPhone = $this->model->where('phone', $datas['phone'])->first();
                if($userCheckPhone && $userCheckPhone->phone == $datas['phone']){
                    return  $this->api->error("Phone Number Has Already Been Registered");
                }
            }
            
            $datas['active'] = 1;
            $newUser = $this->model->create($datas);
            $region = $this->references->where('id', $request->region)->first();
            
            $digits = abs((int)$newUser->id);
            $newUser->nomor_anggota = str_pad($digits, 3, '0', STR_PAD_LEFT).'-'.$region->code.'-1';
            $newUser->eula_accepted = $request->eula_accepted ;
            $newUser->save();

            DB::commit();
            return $this->api->success($newUser, "Successfully Registration");
        } catch(\Throwable $e) {
            DB::rollBack();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function verifikasi_phone_user(verifRequest $request){ //Tombol Continue Hal-7
        DB::beginTransaction();
        try {
            $user = $this->model->where('phone', $request->phone)->first();
            $lenCode = strlen($request->otp_code);

            if(!$user){
                return $this->api->error("Phone Number Not Found");
            }

            if($lenCode < 4 || $lenCode > 4){
                return $this->api->error("OTP code must be 4 digits number");
            }

            if($user->phone_verified_at != null){
                return $this->api->error("Phone Number Has Verified");
            }

            if(now()->gt($user->otp_expired)){
                return $this->api->error("OTP Expired!");
            }

            if($user->otp_code != $request->otp_code){
                return $this->api->error("OTP Code Not Same");
            }

            $user->update([
                'otp_code' => null,
                'otp_expired' => null,
                'phone_verified_at' => now(),
            ]);

            DB::commit();
            return  $this->api->success('Success Verifikasi Phone Number, Verification Successful');
        } catch(\Throwable $e) {
            DB::rollBack();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function continue_registration(Request $request){ //Tombol Send Verification Code Hal-9
        DB::beginTransaction();
        try {
            $datas = $request->all();
            $users = $this->model;

            $emailIsExists = $users->where('email', $request->email)->first();
            $user = $users->where('phone', $request->phone)->first();

            if($emailIsExists && $emailIsExists->flag_done_profile != '1'){
                $emailIsExists->delete();
            }

            if($emailIsExists && $emailIsExists->flag_done_profile == '1'){
                return $this->api->error("Email Has Already Been Registered");
            }

            if(!$user){
                return $this->api->error("User Not Found");
            }

            if(!$user->phone_verified_at){
                return $this->api->error("Phone Number Not Verified");
            }

            $datas['password'] = bcrypt($request->password);
            $datas['otp_code'] = $this->helper->otpCodeFrom($this->model, 'email');
            $datas['otp_expired'] = $this->helper->addTimeFromNow($this->config);
            $diffOtpExpired = $this->helper->addTimeFromNow($this->config, $datas['otp_expired']);

            $user->update($datas);

            $user->otp_expired_second = $this->helper->__timeOtpExpired($user->otp_expired, $diffOtpExpired);

            $email = new VerivicationEmail($user);
            Mail::to($user->email)->send($email);

            DB::commit();

            return $this->api->success($user, "Successfully Continue Registration");
        } catch(\Throwable $e) {
            DB::rollBack();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function verifikasi_email_user(Request $request){ //Tombol Continue Hal-10
        DB::beginTransaction();
        try {
            $user = $this->model->where('email', $request->email)->first();
            $lenCode = strlen($request->otp_code);

            if(!$user){
                return $this->api->error("Email Not Found");
            }

            if($lenCode < 6 || $lenCode > 6){
                return $this->api->error("OTP code must be 6 digits number");
            }

            if($user->email_verified_at){
                return $this->api->error("Email Has Verified");
            }

            if(now()->gt($user->otp_expired)){
                return $this->api->error("OTP Expired!");
            }

            if($user->otp_code != $request->otp_code){
                return $this->api->error("OTP Code Not Same");
            }

            $user->update([
                'otp_code' => null,
                'otp_expired' => null,
                'email_verified_at' => now(),
            ]);

            DB::commit();
            return  $this->api->success('Success Verifikasi Email, Verification Successful');
        } catch(\Throwable $e) {
            DB::rollBack();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function save_profile(Request $request){ //Tombol Save Hal-11
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required',
                'phone' => 'required',
                'gender' => 'required',
                'birth_date' => 'required',
                // 'hcp_index' => 'nullable',
                'faculty' => 'required',
                'm_faculty_id' => 'required',
                'batch' => 'required',
                'office_name' => 'required',
                'address' => 'required',
                'business_sector' => 'required',
                'position' => 'required',
                't_city_id' => 'required',
                'nickname' => 'nullable',
            ]);

            if ($validator->fails()) {
                return $this->api->error_validation($validator);
            }


            $datas = $request->all();
            $dataUser = $this->model->where('phone', $request->phone)->where('email', $request->email)->first();

            if(!$dataUser){
                return  $this->api->error("Data User Not Found");
            }
            unset($datas['phone']);
            unset($datas['email']);
            $datas['birth_date'] = $this->helper->tanggal($datas['birth_date']);
            $datas['flag_done_profile'] = 1;
            $datas['active'] = 1;
            //save photo profile
            $datas['player_id'] = $this->helper->generatePlayerId('player_id', $datas['faculty']);

            $folder = "dgolf/user-profile";
            $column = "image";
            $dataUser->update($datas);
            // $this->helper->compresedUploads($folder, $dataUser, $column);
            $this->helper->uploads($folder, $dataUser, $column);

            //lasung masuk community dari kota yang dia pilih
            //dikomen gak jdi di pake
            // $community = $this->community->where('t_city_id', $dataUser->t_city_id)->first();
            // if($community){
            //     $join = [
            //         't_user_id' => $dataUser->id,
            //         't_community_id' => $community->id,
            //         'active' => 1,
            //     ];
            //     $this->memberCommunity->create($join);
            // }

            DB::commit();
            //send greeting email after user done his profile
            $email = new GreetingMail($dataUser);
            Mail::to($dataUser->email)->send($email);
            return $this->api->store($dataUser, "Success Save Profile");
        } catch(\Throwable $e) {
            DB::rollBack();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function update_profile(Request $request, $id){ //Tombol Save Hal-11
        DB::beginTransaction();
        try {

            $datas = $request->all();
            $datas = $this->helper->removeNullValues($datas);
            $dataUser = $this->model->find($id);

            if(!empty($datas['year_of_entry'])){
                $datas['year_of_entry'] = (int) $datas['year_of_entry'];
            }

            if(!empty($datas['year_of_retirement'])){
                $datas['year_of_retirement'] = (int) $datas['year_of_retirement'];
            }

            // if(!empty($datas['t_city_id'])){
            //     $city_code = ($this->city->where('id', $datas['t_city_id'])->first())->code;
            //     $datas = array_merge($request->all(), ['kota_kabupaten' => $city_code]);
            // }

            if(!empty($datas['birth_date'])){
                $birthDate = explode("-", $datas['birth_date']);
                
                $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[2], $birthDate[1], $birthDate[0]))) > date("md")
                    ? ((date("Y") - $birthDate[0]) - 1)
                    : (date("Y") - $birthDate[0]));
                    
                $datas = array_merge($request->all(), ['age' => $age]);
            }
            
            if(!$dataUser){
                return  $this->api->error("Data User Not Found");
            }
            //save photo profile
            $folder = "rump4t/user-profile";
            $column = "image";
            // $this->helper->compresedUploads($folder, $dataUser, $column);
            $this->helper->uploads($folder, $dataUser, $column);
            // return response()->json($dataUser);
            $datas['image'] = $dataUser->image;
            $dataUser->update($datas);
            DB::commit();
            return $this->api->success($datas, "Success Update Profile");
        } catch(\Throwable $e) {
            DB::rollBack();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function resend_otp(Request $request){ //Tombol resend code
        DB::beginTransaction();
        try {
            $datas = $request->all();
            $users = $this->model;

            if(isset($request->phone)){
                $user = $users->where('phone', $request->phone)->first();

                if(!$user){
                    return  $this->api->error("Phone Number Not Found!");
                }

                if($user->phone_verified_at){
                    return  $this->api->error("Phone Number Already Verified");
                }

                $datas['otp_code']= $this->helper->otpCodeFrom($this->model, 'phone');
                $datas['otp_expired'] = $this->helper->addTimeFromNow($this->config);
                $diffOtpExpired = $this->helper->addTimeFromNow($this->config, $datas['otp_expired']);

                $user->update($datas);

                $user->otp_expired_second = $this->helper->__timeOtpExpired( $user->otp_expired, $diffOtpExpired);

                // $sendSuccess = $this->helper->sendWhatsappFonnte("$user->phone|$user->otp_code");

                // if ($sendSuccess['status'] == false ) {
                //     return $this->api->error('Failed : ' .$sendSuccess['reason']);
                // }

                // $sendSuccess = $this->helper->sendSmsViro($user->phone, $user->otp_code);

                // if($sendSuccess['status'] != 200){
                //     return $this->api->error('Failed : ' .$sendSuccess['requestError']['serviceException']['messageId']. ', WHY : ' .$sendSuccess['requestError']['serviceException']['text'], $sendSuccess['status']);
                // }

                $sendSuccess = $this->helper->sendWhatsappKoala($user->phone, $user->otp_code, $user->otp_expired_second);

                if($sendSuccess['statusCode'] != 200){
                    return $this->api->error('Failed : ' . $sendSuccess['statusMessage']. ', WHY : ' . $sendSuccess['message'], $sendSuccess['statusCode']);
                }
            } else {
                $user = $users->where('email', $request->email)->first();

                if(!$user){
                    return  $this->api->error("Email Not Found!");
                }

                if($user->email_verified_at){
                    return  $this->api->error("Email Already Verified");
                }

                $datas['otp_code']= $this->helper->otpCodeFrom($this->model, 'email');
                $datas['otp_expired'] = $this->helper->addTimeFromNow($this->config);
                $diffOtpExpired = $this->helper->addTimeFromNow($this->config, $datas['otp_expired']);

                $user->update($datas);

                $user->otp_expired_second = $this->helper->__timeOtpExpired($user->otp_expired, $diffOtpExpired);
                //Kirim Via SMS
                $email = new ResendOtpMail($user);
                Mail::to($user->email)->send($email);

                }
            DB::commit();
            return $this->api->success($user, "Success Resend OTP");
        } catch(\Throwable $e) {
            DB::rollBack();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    // Login sync this
    public function get_user(Request $request){  //Tombol Login Hal 4
        try {
            $users = $this->model;

            if($request->phone){
                $user = $users->select('email')->where('phone', $request->phone)->first();

                if(!$user){
                    return  $this->api->error("Phone Number Not Found!");
                }
            } else {
                $user = $users->select('phone')->where('email', $request->email)->first();

                if(!$user){
                    return  $this->api->error("Email Not Found!");
                }
            }

            $credentials = $request->only('email', 'password');
            
            if (!Auth::attempt($credentials)) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            
            $auth_user = Auth::user();

            $data = [
                "user" => [
                    "id" => $auth_user->id ?? null,
                    "name" => $auth_user->name ?? null,
                    "active" => $auth_user->active ?? null,
                    "email" => $auth_user->email ?? null,
                    "phone" => $auth_user->phone ?? null,
                    "region" => $auth_user->region ?? null,
                ]
            ];

            return $this->api->success($data, 'Successfully Login');
        } catch(\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function login_to_send_otp(Request $request){ //Tombol Send Verification Code Hal 6 dan hal 11
        DB::beginTransaction();
        try {
            $users = $this->model;

            if(isset($request->phone)){
                $user = $users->where('phone', $request->phone)->first();
                if($request->phone == '081234567890') {
                    $user->otp_expired_second = 300;
                    return  $this->api->success($user, 'OTP Code Login Has Been Sent');
                }
                if(!$user){
                    return  $this->api->error("Phone Number Not Found!");
                }

                if(!$user->flag_done_profile){
                    return  $this->api->error("Phone Number Not Found!");
                }

                if($user->deleted_at){
                    return $this->api->error("User Has Been Deleted");
                }

                if($user->active != '1'){
                    return  $this->api->error("User Is Non Active");
                }

                if(!$user->phone_verified_at){
                    return $this->api->error("Phone Number Not Verified");
                }

                if(!$user->email_verified_at){
                    return $this->api->error("Email Not Verified");
                }

                $datas['otp_code_login']= $this->helper->otpCodeFrom($this->model, 'email');
                $datas['otp_expired'] = $this->helper->addTimeFromNow($this->config);
                $diffOtpExpired = $this->helper->addTimeFromNow($this->config, $datas['otp_expired']);

                $user->update($datas);

                $user->otp_expired_second = $this->helper->__timeOtpExpired($user->otp_expired, $diffOtpExpired);

                $email = new LoginMail($user);
                Mail::to($user->email)->send($email);

            } else {
                $user = $users->where('email', $request->email)->first();
                if($request->email == 'usertesting@gmail.com') {
                    $user->otp_expired_second = 300;
                    return  $this->api->success($user, 'OTP Code Login Has Been Sent');
                }

                if(!$user){
                    return  $this->api->error("Email Not Found!");
                }

                if(!$user->flag_done_profile){
                    return  $this->api->error("Email Not Found!");
                }

                if($user->deleted_at){
                    return $this->api->error("User Has Been Deleted");
                }

                if($user->active != '1'){
                    return  $this->api->error("User Is Non Active");
                }

                if(!$user->phone_verified_at){
                    return $this->api->error("Phone Number Not Verified");
                }

                if(!$user->email_verified_at){
                    return $this->api->error("Email Not Verified");
                }

                $datas['otp_code_login'] = $this->helper->otpCodeFrom($this->model, 'phone');
                $datas['otp_expired'] = $this->helper->addTimeFromNow($this->config);
                $diffOtpExpired = $this->helper->addTimeFromNow($this->config, $datas['otp_expired']);

                $user->update($datas);

                $user->otp_expired_second = $this->helper->__timeOtpExpired($user->otp_expired, $diffOtpExpired);
                //send otp via sms
                // $sendSuccess = $this->helper->sendWhatsappFonnte("$user->phone|$user->otp_code_login");

                // if ($sendSuccess['status'] == false ) {
                //     return $this->api->error('Failed : ' .$sendSuccess['reason']);
                // }
                // $sendSuccess = $this->helper->sendSmsViro($user->phone, $user->otp_code_login);

                // if($sendSuccess['status'] != 200){
                //     return $this->api->error('Failed : ' .$sendSuccess['requestError']['serviceException']['messageId']. ', WHY : ' .$sendSuccess['requestError']['serviceException']['text'], $sendSuccess['status']);
                // }
                $sendSuccess = $this->helper->sendWhatsappKoala($user->phone, $user->otp_code_login);//, $user->otp_expired_second);

                if($sendSuccess['statusCode'] != 200){
                    return $this->api->error('Failed : ' . $sendSuccess['statusMessage']. ', WHY : ' . $sendSuccess['message'], $sendSuccess['statusCode']);
                }
            }

            DB::commit();
            return  $this->api->success($user, 'OTP Code Login Has Been Sent');
        } catch(\Throwable $e) {
            DB::rollBack();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function verify_login(Request $request){ //Tombol continue hal 7 dan hal 12
        DB::beginTransaction();
        try {

            $users = $this->model;
            $lenOtp = strlen($request->otp_code_login);

            $data = [];

            if(isset($request->phone)){

                $user = $users->where('phone', $request->phone)->first();

                if(!$user){
                    return  $this->api->error("Phone Number Not Found!");
                }

                if($user->active != '1'){
                    return  $this->api->error("User Is Non Active");
                }

                if($lenOtp < 6 || $lenOtp > 6){
                    return  $this->api->error("Invalid OTP Length! Please Enter a 6 Digit OTP");
                }

                if(!$user->phone_verified_at){
                    return  $this->api->error("Phone Number Not Verified");
                }

                if(!$user->email_verified_at){
                    return  $this->api->error("Email Not Verified");
                }

                if(now()->gt($user->otp_expired)){
                    return $this->api->error("OTP Login Expired!, Please Resend OTP");
                }

                if($user->otp_code_login != $request->otp_code_login){
                    return $this->api->error("OTP Login Code Not Same");
                }

                $user->update([
                    'otp_code_login' => null,
                    'otp_expired' => null,
                ]);

                $data = [
                    "token" => $user->createToken('authToken')->accessToken,
                    "user" => [
                        "id" => $user->id ?? null,
                        "name" => $user->name ?? null,
                        "active" => $user->active ?? null,
                        "image" => $user->image ?? null,
                        "email" => $user->email ?? null,
                        "phone" => $user->phone ?? null,
                        "flag_eula" => $user->flag_eula ?? 0,
                        "remember_token" => $user->remember_token ?? null,
                    ]
                ];
                if($request->phone == '081234567890') {
                    $data = [
                        "token" => $user->createToken('authToken')->accessToken,
                        "user" => [
                            "id" => $user->id ?? null,
                            "name" => $user->name ?? null,
                            "active" => $user->active ?? null,
                            "image" => $user->image ?? null,
                            "email" => $user->email ?? null,
                            "phone" => $user->phone ?? null,
                            "remember_token" => $user->remember_token ?? null,
                        ]
                    ];
                    $user->update([
                        'otp_code_login' => 1234,
                        'otp_expired' => null,
                    ]);
                }

                // DB::commit();
                // return  $this->api->success($data, 'Success Login');
            } else {
                $user = $users->where('email', $request->email)->first();

                if(!$user){
                    return  $this->api->error("Email Not Found!");
                }

                if($user->active != '1'){
                    return  $this->api->error("User Is Non Active");
                }

                if($lenOtp < 4 || $lenOtp > 4){
                    return  $this->api->error("Invalid OTP Length! Please Enter a 4 Digit OTP");
                }

                if(!$user->email_verified_at){
                    return  $this->api->error("Email Not Verified");
                }

                if(!$user->phone_verified_at){
                    return  $this->api->error("Phone Number Not Verified");
                }

                if(now()->gt($user->otp_expired)){
                    return $this->api->error("OTP Login Expired!, Please Resend OTP");
                }

                $user->update([
                    'otp_code_login' => null,
                    'otp_expired' => null,
                ]);

                $data = [
                    "token" => $user->createToken('authToken')->accessToken,
                    "user" => [
                        "id" => $user->id ?? null,
                        "name" => $user->name ?? null,
                        "active" => $user->active ?? null,
                        "image" => $user->image ?? null,
                        "email" => $user->email ?? null,
                        "phone" => $user->phone ?? null,
                        "flag_eula" => $user->flag_eula ?? 0,
                        "remember_token" => $user->remember_token ?? null,
                    ]
                ];

                if($request->email == 'usertesting@gmail.com') {
                    $data = [
                        "token" => $user->createToken('authToken')->accessToken,
                        "user" => [
                            "id" => $user->id ?? null,
                            "name" => $user->name ?? null,
                            "active" => $user->active ?? null,
                            "image" => $user->image ?? null,
                            "email" => $user->email ?? null,
                            "phone" => $user->phone ?? null,
                            "remember_token" => $user->remember_token ?? null,
                        ]
                    ];
                    $user->update([
                        'otp_code_login' => 1234,
                        'otp_expired' => null,
                    ]);
                }
                // DB::commit();
                // return  $this->api->success($data, 'Success Login');
            }

            DB::commit();
            return  $this->api->success($data, 'Success Login');
        } catch(\Throwable $e) {
            DB::rollBack();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function resend_otp_login(Request $request){ // Tombol resend code di Login
        DB::beginTransaction();
        try {
            $datas = $request->all();
            $users = $this->model;

            if(isset($request->phone)){
                $user = $users->where('phone', $request->phone)->first();

                if(!$user){
                    return  $this->api->error("Phone Number Not Found!");
                }

                if(!$user->phone_verified_at){
                    return  $this->api->error("Phone Number Not Verified");
                }

                if(!$user->email_verified_at){
                    return  $this->api->error("Email Not Verified");
                }

                $datas['otp_code_login']= $this->helper->otpCodeFrom($this->model, 'email');
                $datas['otp_expired'] = $this->helper->addTimeFromNow($this->config);
                $diffOtpExpired = $this->helper->addTimeFromNow($this->config, $datas['otp_expired']);

                $user->update($datas);

                $user->otp_expired_second = $this->helper->__timeOtpExpired($user->otp_expired, $diffOtpExpired);

                $email = new LoginMail($user);
                Mail::to($user->email)->send($email);
            } else {
                $user = $users->where('email', $request->email)->first();

                if(!$user){
                    return  $this->api->error("Email Not Found!");
                }

                if(!$user->email_verified_at){
                    return  $this->api->error("Email Not Verified");
                }

                if(!$user->phone_verified_at){
                    return  $this->api->error("Phone Number Not Verified");
                }

                $datas['otp_code_login']= $this->helper->otpCodeFrom($this->model, 'phone');
                $datas['otp_expired'] = $this->helper->addTimeFromNow($this->config);
                $diffOtpExpired = $this->helper->addTimeFromNow($this->config, $datas['otp_expired']);

                $user->update($datas);

                $user->otp_expired_second = $this->helper->__timeOtpExpired($user->otp_expired, $diffOtpExpired);
                //Kirim Via SMS
                // $sendSuccess = $this->helper->sendWhatsappFonnte("$user->phone|$user->otp_code_login");

                // if ($sendSuccess['status'] == false ) {
                //     return $this->api->error('Failed : ' .$sendSuccess['reason']);
                // }

                // $sendSuccess = $this->helper->sendSmsViro($user->phone, $user->otp_code_login);

                // if($sendSuccess['status'] != 200){
                //     return $this->api->error('Failed : ' .$sendSuccess['requestError']['serviceException']['messageId']. ', WHY : ' .$sendSuccess['requestError']['serviceException']['text'], $sendSuccess['status']);
                // }
                $sendSuccess = $this->helper->sendWhatsappKoala($user->phone, $user->otp_code_login, $user->otp_expired_second);

                if($sendSuccess['statusCode'] != 200){
                    return $this->api->error('Failed : ' . $sendSuccess['statusMessage']. ', WHY : ' . $sendSuccess['message'], $sendSuccess['statusCode']);
                }
            }
            DB::commit();
            return $this->api->success($user, "Success Resend OTP");
        } catch(\Throwable $e) {
            DB::rollBack();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function getAuth(){
        try {
            $id = Auth::id();
            $user = User::with(['community', 'membersCommonity', 'city'])->find($id);
            $member = MembersCommonity::where('t_user_id', $user->id)->first();
            $region = $this->references
                ->where('parameter', 'm_region')
                ->where('id', $user->region)
                ->get();
            
            $performa = $this->performanceController->box()->getData();
            $hcpIndex = $performa->data->handicapIndex;
            $dataCompany = $this->companyProfile->first();

            $view = [
                'user' => [
                    "id" => $user->id,
                    "player_id" => $user->player_id,
                    "name" => $user->name,
                    "email" => $user->email,
                    "phone" => $user->phone,
                    "gender" => $user->gender,
                    "birth_date" => $user->birth_date,
                    "address" => $user->address,
                    "position" => $user->position,
                    "image" => $user->image,
                    "t_city_id" => $user->city->id ?? null,
                    "city" => $user->city->name ?? null,
                    "t_community_id" => $user->community->id ?? null,
                    "community" => $user->community->title ?? null,
                    "member_since" => Carbon::parse($user->created_at)->format('d/m/Y'),
                    
                    "url_barcode" => url('/rump4t/profile-user/' . $this->helper->encryptDecrypt($id)),
                    // "nickname" => $user->nickname ?? null,
                    // "hcp_index" => $user->hcp_index,
                    // "faculty" => $user->faculty,
                    // "batch" => $user->batch,
                    // "office_name" => $user->office_name,
                    // "business_sector" => $user->business_sector,
                    // "fcm_token" => $user->fcm_token,
                    // "flag_community" => $user->flag_community,
                    // "handicap_index" => $hcpIndex,
                    "url_barcode" => url('/rump4t/profile-user/' . $this->helper->encryptDecrypt($id)),
                    "eula_accepted" => $user->eula_accepted,

                    "birth_place" => $user->birth_place,
                    "age" => $user->age,
                    "desa_kelurahan" => $user->village->name ?? null,
                    "kecamatan" => $user->district->name ?? null,
                    "kota_kabupaten" => $user->regency->name ?? null,
                    "postal_code" => $user->postal_code,
                    "provinsi" => $user->province->name ?? null,
                    "year_of_entry" => $user->year_of_entry,
                    "year_of_retirement" => $user->year_of_retirement,
                    "retirement_type" => $user->retirement_type,
                    "last_employee_status" => $user->last_employee_status,
                    "last_division" => $user->last_division,
                    "spouse_name" => $user->spouse_name,
                    "shirt_size" => $user->shirt_size,
                    "notes" => $user->notes,
                    "ec_name" => $user->ec_name,
                    "ec_kinship" => $user->ec_kinship,
                    "ec_contact" => $user->ec_contact,
                    "region" => $region,
                    "status_anggota" => $user->status_anggota,
                    "nomor_anggota" => $user->nomor_anggota,
                ],
                'our_contact' => [
                    "id" => $dataCompany->id,
                    "name" => $dataCompany->name,
                    "address" => $dataCompany->address,
                    "email" => $dataCompany->email,
                    "phone" => $dataCompany->phone,
                    "whatsapp" => $dataCompany->whatsapp,
                    "website" => $dataCompany->website,
                    "instagram" => $dataCompany->instagram,
                    "facebook" => $dataCompany->facebook,
                    "twitter" => $dataCompany->twitter,
                    "youtube" => $dataCompany->youtube,
                ],
                "term_condition" => $dataCompany->term_condition,
                "privacy_policy" => $dataCompany->privacy_policy,
            ];

            return $this->api->success($view, "Hallo $user->name");
        } catch(\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public  function update_cross(Request $request){ //Tombol Send Verification Code Hal-6
        DB::beginTransaction();
        try {
            $datas = $request->all();
            $id = Auth::id();
            $user = $this->model->findOrFail($id);

            $check = $this->model->where('id', '!=', $user->id);

            // $otp['otp_code'] = $this->helper->otpCodeFrom($this->model, ($datas['send_to'] ? 'email' : 'phone'));
            $otp['otp_expired'] = $this->helper->addTimeFromNow($this->config);
            $diffOtpExpired = $this->helper->addTimeFromNow($this->config, $otp['otp_expired']);

            if(isset($request->new_phone)){
                $isExists = $check->where('phone', $request->new_phone)->exists();
                if($isExists){
                    return $this->api->error('New Phone Number is already exists!');
                }

                $data = [
                    't_user_id' => $id,
                    'type' => 'UPD',
                    'phone' => $request->new_phone,
                    'email' => $user->email,
                    'otp_code' => $this->helper->otpCodeFrom($this->model, 'email'),
                    'otp_expired' => $otp['otp_expired'],
                ];
                $new_data = 'phone'; //data yang di update
                $user->otp_code = $data['otp_code'];
                $email = new VerificationUpdateMail($user);
                Mail::to($user->email)->send($email);
            } else {
                $isExists = $check->where('email', $request->new_email)->exists();
                if($isExists){
                    return $this->api->error('New Email is already exists!');
                }

                $data = [
                    't_user_id' => $id,
                    'type' => 'UPD',
                    'email' => $request->new_email,
                    'phone' => $user->phone,
                    'otp_code' => $this->helper->otpCodeFrom($this->model, 'phone'),
                    'otp_expired' => $otp['otp_expired'],
                ];
                $new_data = 'email'; //data yang di update
                // $sendSuccess = $this->helper->sendWhatsappFonnte($user->phone.'|'.$data['otp_code']);
                // if ($sendSuccess['status'] == false ) {
                //     return $this->api->error('Failed : ' .$sendSuccess['reason']);
                // }
                // $sendSuccess = $this->helper->sendSmsViro($user->phone, $data['otp_code']);
                // if($sendSuccess['status'] != 200){
                //     return $this->api->error('Failed : ' .$sendSuccess['requestError']['serviceException']['messageId']. ', WHY : ' .$sendSuccess['requestError']['serviceException']['text'], $sendSuccess['status']);
                // }
                $otp_expired_second = $this->helper->__timeOtpExpired($otp['otp_expired'], $diffOtpExpired);
                $sendSuccess = $this->helper->sendWhatsappKoala($user->phone, $data['otp_code'], $otp_expired_second);

                if($sendSuccess['statusCode'] != 200){
                    return $this->api->error('Failed : ' . $sendSuccess['statusMessage']. ', WHY : ' . $sendSuccess['message'], $sendSuccess['statusCode']);
                }
            }
            $user->update($datas);

            $otp_expired_second = $this->helper->__timeOtpExpired($data['otp_expired'], $diffOtpExpired);

            Otp::where('t_user_id', $id)->whereNull('deleted_at')->where(
                function($q) {
                    $q->whereNull('email_verified_at')->orWhereNull('phone_verified_at');
                }
            )->delete();

            $store = Otp::create($data);

            $view = [
                'new_'.$new_data => $store->{$new_data},
                'otp_code' => $store->otp_code,
                'otp_expired' => $store->otp_expired,
                'otp_expired_second' => $otp_expired_second,
            ];

            DB::commit();
            return $this->api->success($view, "Successfully");
        } catch(\Throwable $e) {
            DB::rollBack();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function verify_update_cross(Request $request){ //Tombol Continue Hal-7
        DB::beginTransaction();
        try {
            $id = Auth::id();
            $user = $this->model->findOrFail($id);
            $otps = Otp::where('t_user_id', $user->id)->where('type', 'UPD')->where('is_verified', false)->whereNull('deleted_at');

            if(isset($request->new_phone)){
                $otp = $otps->where('phone', $request->new_phone)->where('email', $user->email)->whereNull('email_verified_at')->first();
                $crossVerified_at = 'email_verified_at';
            } else {
                $otp = $otps->where('email', $request->new_email)->where('phone', $user->phone)->whereNull('phone_verified_at')->first();
                $crossVerified_at = 'phone_verified_at';
            }

            if($request->otp_code != $otp->otp_code){
                return  $this->api->error('OTP Code is wrong!');
            }

            if(now()->gt($otp->otp_expired)){
                return  $this->api->error('The OTP has expired! Please request a new one.');
            }

            $otp->update([
                'otp_code' => null,
                'otp_expired' => null,
                'type' => null,
                $crossVerified_at => now(),
            ]);

            $user->update([
                $crossVerified_at => now(),
            ]);

            if($crossVerified_at == 'phone_verified_at'){
                $dataOtp['type'] = "UPD";
                $dataOtp['otp_code'] = $this->helper->otpCodeFrom($this->model, 'email');
                $dataOtp['otp_expired'] = $this->helper->addTimeFromNow($this->config);
                $diffOtpExpired = $this->helper->addTimeFromNow($this->config, $dataOtp['otp_expired']);
                $massage = 'Email';
                $massage2 = 'Phone Number';
                $dataVerify = 'email';

                $otp->update($dataOtp);
                $otp->name = $user->name;
                $otp->otp_expired_second = $this->helper->__timeOtpExpired($dataOtp['otp_expired'], $diffOtpExpired);

                $email = new VerificationUpdateMail($otp);
                Mail::to($otp->email)->send($email);
            } else {
                $dataOtp['type'] = "UPD";
                $dataOtp['otp_code'] = $this->helper->otpCodeFrom($this->model, 'phone');
                $dataOtp['otp_expired'] = $this->helper->addTimeFromNow($this->config);
                $diffOtpExpired = $this->helper->addTimeFromNow($this->config, $dataOtp['otp_expired']);
                $massage = 'Phone Number';
                $massage2 = 'Email';
                $dataVerify = 'phone';

                $otp->update($dataOtp);
                $otp->otp_expired_second = $this->helper->__timeOtpExpired($dataOtp['otp_expired'], $diffOtpExpired);

                // $sendSuccess = $this->helper->sendWhatsappFonnte("$otp->phone|$otp->otp_code");
                // if ($sendSuccess['status'] == false ) {
                //     return $this->api->error('Failed : ' .$sendSuccess['reason']);
                // }
                // $sendSuccess = $this->helper->sendSmsViro($otp->phone, $otp->otp_code);

                // if($sendSuccess['status'] != 200){
                //     return $this->api->error('Failed : ' .$sendSuccess['requestError']['serviceException']['messageId']. ', WHY : ' .$sendSuccess['requestError']['serviceException']['text'], $sendSuccess['status']);
                // }
                $sendSuccess = $this->helper->sendWhatsappKoala($otp->phone, $dataOtp['otp_code'], $user->otp_expired_second);

                if($sendSuccess['statusCode'] != 200){
                    return $this->api->error('Failed : ' . $sendSuccess['statusMessage']. ', WHY : ' . $sendSuccess['message'], $sendSuccess['statusCode']);
                }
            }

            $view = [
                'new_'.$dataVerify => $otp->{$dataVerify},
                'otp_code' => $otp->otp_code,
                'otp_expired' => $otp->otp_expired,
                'otp_expired_second' => $otp->otp_expired_second,
            ];

            DB::commit();
            return  $this->api->success($view, "Success Verified $massage2, Please Check Your $massage");
        } catch(\Throwable $e) {
            DB::rollBack();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function verify_new_update_email_phone(Request $request){
        DB::beginTransaction();
        try {
            $id = Auth::id();
            $user = $this->model->findOrFail($id);

            $otps = Otp::where('t_user_id', $user->id)->where('type', 'UPD')->where('is_verified', false)->whereNull('deleted_at');

            if(isset($request->new_phone)){
                $otp = $otps->where('phone', $request->new_phone)->where('email', $user->email)->whereNull('phone_verified_at')->first();
                $verified_at = 'phone_verified_at';
                $dataVerify = 'phone';
            } else {
                $otp = $otps->where('email', $request->new_email)->where('phone', $user->phone)->whereNull('email_verified_at')->first();
                $verified_at = 'email_verified_at';
                $dataVerify = 'email';
            }

            if($request->otp_code != $otp->otp_code){
                return  $this->api->error('OTP Code is wrong!');
            }

            if(now()->gt($otp->otp_expired)){
                return  $this->api->error('The OTP has expired! Please request a new one.');
            }

            $user->update([
                $dataVerify => $otp->{$dataVerify},
                $verified_at => now(),
            ]);

            $otp->update([
                'is_verified' => true,
                'deleted_at' => now(),
            ]);

            DB::commit();
            return $this->api->success($user, "$dataVerify Has Been Verified");
        } catch(\Throwable $e) {
            DB::rollBack();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function resend_verify_otp(){
        DB::beginTransaction();
        try {
            $id = Auth::id();
            $user = $this->model->findOrFail($id);

            DB::commit();
        } catch(\Throwable $e) {
            DB::rollBack();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function allow_notification(Request $request)
    {
        DB::beginTransaction();
        try {
            $id = Auth::id();
            $user = $this->model->where('id', $id)->first();

            if(!$user){
                return  $this->api->error("User Not Found!");
            }

            $user->update([
                'fcm_token' => $request->fcm_token,
            ]);

            DB::commit();
            return $this->api->success($user, "Successfully Allow Notified!");
    
        } catch(\Throwable $e){
            DB::rollBack();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }


    public function push_notification(Request $request)
    {
        try {
            $fcm_token = Auth::user()->fcm_token;

            if($fcm_token == null || $fcm_token == ""){
                return $this->api->error("Your Phone Not Allowed Notification");
            }

            $cek = $this->helper->pushNotification1($fcm_token, $request->title, $request->message);

            return $this->api->success($cek, 'Success Sending Message');
        } catch(\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }


    public function list_region()
    {
        $list_region = $this->references
            ->where('parameter', 'm_region')
            ->get();

        return $this->api->success($list_region, 'success');
    }

    public function list_area($region_id)
    {
        $list_area = $this->references
            ->where('parameter', 'm_area')
            ->where('parent_id', $region_id)
            ->get();

        return $this->api->success($list_area, 'success');
    }

    public function loginCepat(Request $request){
        
        DB::beginTransaction();
        try {
            

         
            
            $request->validate([
                'identifier' => 'required|string', // bisa phone atau nomor_anggota
                'password' => 'required|string',
            ]);
        
            // Cari user berdasarkan nomor_anggota atau phone
            $user = User::where('nomor_anggota', $request->identifier)
                        ->orWhere('phone', $request->identifier)
                        ->first();
        
            if ($user && Hash::check($request->password, $user->password)) {
                if($user->active == 1){
                    Auth::login($user);
                }else{
                    return $this->api->error('User Inactive');
                }
            }else{
                return $this->api->error('Invalid Credentials');
            }




            
            $user = Auth::user();

            $data = [
                "token" => $user->createToken('authToken')->accessToken,
                "user" => [
                    "id" => $user->id ?? null,
                    "name" => $user->name ?? null,
                    "active" => $user->active ?? null,
                    "email" => $user->email ?? null,
                    "phone" => $user->phone ?? null,
                    "region" => $user->region ?? null,
                    "remember_token" => $user->remember_token ?? null,
                    "image" => $user->image ?? null,
                    "eula_accepted" => $user->eula_accepted,
                    "nomor_anggota" => $user->nomor_anggota ?? null,
                ]
            ];

            DB::commit();
            return $this->api->success($data, 'sukses login');
        } catch(\Throwable $e) {
            DB::rollBack();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage() . " ". $e->getFile().":".$e->getLine(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }


    public function user_reset_password(Request $request) {
        try {
            $user = User::where('email', $request->identifier)->orWhere("phone" , $request->identifier)->orWhere("nomor_anggota" , $request->identifier)->first();
            if(!$user){
                return $this->api->error("User Not Found!");
            }
            DB::beginTransaction();
            // update reset_request to true
            $user->update([
                "reset_request" => true
            ]);


    }catch (\Exception $err) {
        DB::rollBack();
        return $this->api->error($err->getMessage());

    }
    DB::commit();
}

public function accept_eula (Request $req) { 
    try {
        DB::beginTransaction();
        // updating current table data
        $user = User::where("id" ,  "=" , Auth::user()->id) ; 
        $user->update(["eula_accepted" => true]);
    }catch(Exception $e) {
        DB::rollBack();
        return $this->api->error(message: $e->getMessage());
    }
    DB::commit();
        return $this->api->success($user, "success");
    
}

public function user_reset_request(Request $request) {
    if(empty($request->identifier)){
        return $this->api->error(message: "email or phone tidak ada.");
    }
    
    $user = User::where('email', $request->identifier)
    ->orWhere("phone" , $request->identifier)
    ->orWhere("nomor_anggota" , $request->identifier)->first();

    DB::beginTransaction();
    try {
        $user->update([
                "reset_request" => true
        ]);
    }catch (\Exception $err) {
        DB::rollBack();
        return $this->api->error($err->getMessage());

    }
    DB::commit();
    return $this->api->success($user, "Success Request Reset Password");
}

    public function delete_account(Request $request){
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $accessToken = $user->token();
            // $request->user()->update([
            //     'deleted_at' => now(),
            // ]);
            MemberEvent::where('t_user_id', $user->id)->delete();
            MembersCommonity::where('t_user_id', $user->id)->delete();
            MemberLetsPlay::where('t_user_id', $user->id)->delete();
            ScoreHandicap::where('t_user_id', $user->id)->delete();

            $token = $request->user()->tokens->find($accessToken);
            $token->revoke();

            $request->user()->delete();
            DB::commit();
            return $this->api->success(null, "Success Delete Account");
        } catch(\Throwable $e) {
            DB::rollBack();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function aggree_with_eula(Request $request){
        DB::beginTransaction();
        try {
            $datas = $request->validate([
                'flag_eula' => 'required|integer|boolean'
            ]);
            $user = $this->model->findOrFail(auth()->user()->id);
            $user->update($datas);
            DB::commit();
            return $this->api->success(true);
        } catch(\Throwable $e) {
            DB::rollBack();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            }
        }
    }

    public function tamplat(){
        DB::beginTransaction();
        try {

            DB::commit();
        } catch(\Throwable $e) {
            DB::rollBack();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function get_user_by_id($id)
    {
        $users = $this->model;
        $user = $users->where('id',(int) $id)->first();
        return response()->json($user);
    }

    public function logout()
    {
        if(Auth::check()){
            $token = Auth::user()->token();
            $token->revoke();
            return $this->api->success(null, "Success Logout");
        }
    }

    public function list_city()
    {
        $city = $this->city->all();
        return $this->api->success($city, 'success');
    }

    public function total_user()
    {
        $count_user = count($this->model->all());
        return $this->api->success($count_user, "success");
    }

    public function total_member()
    {
        $count_member = count($this->model->where("status_anggota" , 1)->get());
        return $this->api->success($count_member, "success");
    }

    public function total_member_khusus()
    {
        $count_member_khusus = count($this->model->where('status_anggota',2)->get());
        return $this->api->success($count_member_khusus, "success");
    }

    public function search_by_name($name)
    {
        $users = $this->model->where('name', 'ILIKE', '%'.$name.'%')->get();
        return $this->api->success($users, "success");
    }

    public function search_by_region($region)
    {
        $regions = $this->references
            ->where('parameter', 'm_region')
            ->where('value', 'ILIKE', '%'.$region.'%')
            ->get();
            
        return $this->api->success($regions, "success");
    }

    public function selected_region($id)
    {
        $areas = $this->references
            ->where('parameter', 'm_area')
            ->where('parent_id', $id)
            ->get();

        if(count($areas) > 0){
            return $this->api->success($areas, "success");
        }

        $users = $this->model->where('region', $id)->get();
        return $this->api->success($users, "success");
        
    }

    public function selected_area($id)
    {
        $users = $this->model->where('region', $id)->get();
        return $this->api->success($users, "success");
    }

    /* kota: old version */
    public function search_by_city($name)
    {
        $cities = $this->city->where('name', 'ILIKE', '%'.$name.'%')->get();

        return $this->api->success($cities, "success");
    }

    
    public function selected_city($id)
    {
        $users = $this->model->where('t_city_id', $id)->get();
        
        return $this->api->success($users, "success");
    }

    /* kota: new version */
    public function search_by_regency($name)
    {
        $cities = $this->regency->where('name', 'ILIKE', '%'.$name.'%')->get();

        return $this->api->success($cities, "success");
    }

    public function selected_regency($id)
    {
        $users = $this->model->with(['province','regency','district','village'])->where('kota_kabupaten', $id)->get();

        return $this->api->success($users, "success");
    }
}
