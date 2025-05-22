<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use Hash;
use App\Models\User;
use App\Exceptions\Handler;
use Illuminate\Http\Request;
use App\Services\ApiResponse;
use App\Services\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Performace\App\Http\Controllers\PerformaceController;
use Modules\Masters\App\Http\Controllers\MastersController;

class AuthWebController extends Controller
{
    protected $model;
    protected $api;
    protected $handler;
    protected $helper;
    protected $performanceController;
    protected $mastersController;

    public function __construct(User $model, ApiResponse $api, Handler $handler, Helper $helper, PerformaceController $performanceController, MastersController $mastersController)
    {
        $this->model = $model;
        $this->api = $api;
        $this->handler = $handler;
        $this->helper = $helper;
        $this->performanceController = $performanceController;
        $this->mastersController = $mastersController;
    }

    public function home(){
        $data = [
            'content' => 'Admin/home',
            'banner_slide' => json_decode(json_encode($this->mastersController->banner_slide()), true)['original']['data'],
        ];
        
        return view('Admin.Layouts.wrapper', $data);
    }

    public function home_manage(){
        $data = [
            'content' => 'ManagePeople/home'
        ];
        return view('ManagePeople.Layouts.wrapper', $data);
    }

    public function home_manage_event(){
        $data = [
            'content' => 'ManageEvent/home'
        ];
        return view('ManageEvent.Layouts.wrapper', $data);
    }

    public function view_login(){
        return view('Admin.Sign.signIn');
    }

    // public function login(Request $request){
    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required',
    //         'password' => 'required'
    //     ]);
    
    //     if ($validator->fails()) {
    //         return back()->withErrors($validator);
    //     }

    //     $credentials = $request->only('email', 'password');

    //     if (!Auth::attempt($credentials)) {
    //         return back()->withErrors([
    //             'loginError' => 'Email atau password yang Anda masukkan tidak sesuai.',
    //         ])->withInput();
    //     }

    //     $user = Auth::user();

    //     if ($user->t_group_id == null || $user->t_group_id == '') {
    //         Auth::logout();
    //         return back()->withErrors([
    //             'loginError' => 'Your Not Manage People',
    //         ]);
    //     }

    //     if ($user->active != '1') {
    //         Auth::logout();
    //         return back()->withErrors([
    //             'loginError' => 'User Not Active',
    //         ]);
    //     }

    //     return redirect('home');
    // }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);
    
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');
        
        // jika menggunakan phone atau nomor anggota aktifkan ini
        // $user = User::where('nomor_anggota', $credentials['email'])
        //                 ->orWhere('phone', $credentials['email'])
        //                 ->first();

        // $authenticate = ($user && Hash::check($request->password, $user->password)) ?? '';
        
        // if($authenticate){
        //     $credentials['email'] = $user->email;
        // }
        // end.

        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'loginError' => 'Email atau password yang Anda masukkan tidak sesuai.',
            ])->withInput();
        }

        if (!Auth::check()) {
            return back()->withErrors([
                'loginError' => 'Failed to authenticate user.',
            ]);
        }
    
        $user = Auth::user();

        if (empty($user->is_admin)) {
            Auth::logout();
            return back()->withErrors([
                'loginError' => 'You are not authorized to Admin or manage people.',
            ]);
        }

        if ($user->active != '1') {
            Auth::logout();
            return back()->withErrors([
                'loginError' => 'User is not active.',
            ]);
        }

            if($user->is_admin === 1) {
                return redirect('admin/home');
            } else if($user->is_admin === 2) {
                if(empty($user->t_community_id)) {
                    Auth::logout();
                    return back()->withErrors([
                        'loginError' => 'You Not Have Community.',
                    ]);
                }
                return redirect('manage-people/home');
            } else if($user->is_admin === 3) {
                if(empty($user->t_community_id)) {
                    Auth::logout();
                    return back()->withErrors([
                        'loginError' => 'You Not Have Community.',
                    ]);
                }
                return redirect('manage-event/home');
            } else {
                Auth::logout();
                return back()->withErrors([
                    'loginError' => 'You are not authorized to Admin or Manage People.',
                ]);
            }
    }

    public function logout(){
        Auth::logout();
        return redirect('');
    }

    public function webViewProfile($id)
    {
        try{
            $id = $this->helper->encryptDecrypt($id, false);
            $performa = $this->performanceController->box($id)->getData();
            $hcpIndex = $performa->data->handicapIndex;
            $data = [
                'datas' =>  $this->model->findOrfail($id),
                'hcp' => $hcpIndex,
                'url_bg' => asset('images/bacground.jpg'),
            ];
            return view('profileUser', $data);
        } catch (\Throwable $e) {
            return $this->handler->handleExceptionWeb($e);
        }
    }
}
