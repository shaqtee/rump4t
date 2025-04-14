<?php

namespace App\Services\Helpers;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use GuzzleHttp\Client;
use GoogleMaps\GoogleMaps;
use Illuminate\Support\Str;
use App\Models\CompanyProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Crypt;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
// use Intervention\Image\ImageManagerStatic;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Laravel\Firebase\Facades\Firebase;
// use Intervention\Image\Laravel\Facades\Image;
use Modules\Community\App\Models\MemberEvent;
use Modules\Masters\App\Models\MasterReferences;
use Modules\Masters\App\Models\MasterConfiguration;

class Helper
{
    protected $gMaps;
    protected $users;
    protected $config;
    protected $memberEvent;
    protected $notification;

    public function __construct(GoogleMaps $gMaps,  User $users, MasterConfiguration $config, MemberEvent $memberEvent)
    {
        $this->gMaps = $gMaps;
        $this->users = $users;
        $this->config = $config;
        $this->memberEvent = $memberEvent;
        $this->notification = Firebase::messaging();
    }
     /**
     * Helper Upload file anything to S3
     *
     *
     */
    public static function uploads($folder, $model, $key = 'files', $options = 's3')
    {
        if (!empty(request()->hasFile($key))) {
            if (request()->isMethod('post')) {
                $path = request()->file($key)->store($folder, $options);
                if ($path) {
                    $url = Storage::url($path);
                    $model->update([
                        $key => $url,
                    ]);
                }
            }

            if (request()->isMethod('put') || request()->isMethod('patch')) {
                $columnFile = $model->$key;
                if (!empty($columnFile)) {
                    $lastArray = explode('/', $columnFile);
                    $filename = array_pop($lastArray);
                    if (Storage::exists($folder . '/' . $filename)) {
                        Storage::delete($folder . '/' . $filename);
                    }
                }
                $path = request()->file($key)->store($folder, $options);
                if ($path) {
                    $url = Storage::url($path);
                    $model->update([
                        $key => $url,
                    ]);
                }
            }
            return true;
        } else {
            return false;
        }
    }

    public static function compresedUploads($folder, $model, $key = 'files', $options = 's3' , $percent = 0.5)
    {
        if (request()->hasFile($key)) {
            $manager = new ImageManager(new Driver());

            $file = request()->file($key);
            $image = $manager->read($file->getPathname());

            // $percent = 0.5;
            $width = $image->width();
            $height = $image->height();
            $newWidth = $width * $percent;
            $newHeight = $height * $percent;

            $image->resize($newWidth, $newHeight, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $imageData = $image->toJpeg(80);

            $filePath = $folder . '/' . $file->hashName();

            Storage::disk($options)->put($filePath, $imageData, 'public');

            // Storage::disk($options)->put($filePath, $imageStream->__toString(), 'public');

            // $file = request()->file($key);

            // $manager = new ImageManager(new Driver());
            // $image = $manager->read($file);

            // $percent = 0.5;
            // $width = $image->width();
            // $height = $image->height();

            // $newWitdh = $width * $percent;
            // $newHeigth = $height * $percent;
            // $image->resize($newWitdh, $newHeigth, function ($constraint) {
            //     $constraint->aspectRatio();
            //     $constraint->upsize();
            // });

            // $tempPath = sys_get_temp_dir() . '/' . $file->hashName();
            // $image->save($tempPath);

            // $path = Storage::disk($options)->put($folder. '/' . $file->hashName(), fopen($tempPath, 'r+'), 'public');
            // $file = request()->file('your_image_field'); // Ganti 'your_image_field' dengan field image Anda
            // $image = Image::make($file);

            // // Resize image with aspect ratio and upsize
            // $image->resize($newWidth, $newHeight, function ($constraint) {
            //     $constraint->aspectRatio();
            //     $constraint->upsize();
            // });

            // // Create a stream from the image
            // $imageStream = $image->stream('jpg', 80); // Anda bisa mengganti 'jpg' dengan format yang diinginkan dan 80 adalah kualitas kompresi

            // // Upload directly to S3
            // $path = Storage::disk($options)->put($folder . '/' . $file->hashName(), $imageStream->__toString(), 'public');
            // $path = $file->store($folder, $options);
            // unlink($tempPath);

            if ($filePath) {
                $url = Storage::disk($options)->url($filePath);
                $model->update([
                    $key => $url,
                ]);
            }

            // Untuk metode POST, PUT, dan PATCH
            if (request()->isMethod('put') || request()->isMethod('patch')) {
                $columnFile = $model->$key;
                if (!empty($columnFile)) {
                    $lastArray = explode('/', $columnFile);
                    $filename = array_pop($lastArray);
                    if (Storage::exists($folder . '/' . $filename)) {
                        Storage::delete($folder . '/' . $filename);
                    }
                }

                $manager = new ImageManager(new Driver());

                $file = request()->file($key);
                $image = $manager->read($file->getPathname());

                $percent = 0.5;
                $width = $image->width();
                $height = $image->height();
                $newWidth = $width * $percent;
                $newHeight = $height * $percent;

                $image->resize($newWidth, $newHeight, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                $imageData = $image->toJpeg(80);

                $filePath = $folder . '/' . $file->hashName();

                Storage::disk($options)->put($filePath, $imageData, 'public');

                if ($filePath) {
                    $url = Storage::url($filePath);
                    $model->update([
                        $key => $url,
                    ]);
                }

            }

            return true;
        } else {
            return false;
        }
    }

    public static function deleteUploads($folder, $model, $key = 'files', $options = 's3')
    {
        if (!empty(request()->hasFile($key))) {
            if (request()->isMethod('delete')) {
                $columnFile = $model->$key;
                if (!empty($columnFile)) {
                    $lastArray = explode('/', $columnFile);
                    $filename = array_pop($lastArray);
                    if (Storage::exists($folder . '/' . $filename)) {
                        Storage::delete($folder . '/' . $filename);
                    }
                    $model->update([
                        $key => null,
                    ]);
                }
            }
            return true;
        } else {
            return false;
        }
    }

    public static function Columns($arr = [])
    {
        $datas = collect();
        foreach ($arr as $key => $val) {
            $snake = Str::snake($key);
            if (is_array($val)) {
                $values = collect();
                foreach ($val as $k => $v) {
                    $values->push([
                        'label' => $k,
                        'value' => $v,
                    ]);
                }
                $data = [$snake => [
                    'Label' => $key,
                    'type' => 'array',
                    'values' => $values->toArray(),
                ]];
                $datas->push($data);
            } else {
                $data = [$snake => [
                    'Label' => $key,
                    'type' => $val,
                ]];
                $datas->push($data);
            }
        }

        return $datas;
    }

    public function sendWhatsappFonnte($numbersAndOtp){
        $message = MasterConfiguration::where('parameter', 'whatsapp_massage_template')->first();
        $url = env('FONNTE_ENDPOINT') . '/send';
        $headers = [
            'Authorization' => env('FONNTE_AUTH'),
        ];
        $body = [
            'target' => $numbersAndOtp,
            'message' => "$message->value1"
        ];

        $shoot = Http::withHeaders($headers)->post($url, $body);

        $responseBody = $shoot->body();
        $json = $this->covertToJson($responseBody, true);

        if ($json['status'] == false) {
            return $json;
        }

        // d?ump('Body : '. $response['reason']);
        // dump('Json : '. $response->json());
        // dump('Status : '. $response->status());
        // dump('Success : '. $response->successful());
        // dump('Failed : '. $response->failed());
        // dump('Ok : ' . $response->ok());
        // dump('ClientError : '. $response->clientError());
        // dump('ServerError : '. $response->serverError());
        // dump('Headers : '. $response->headers());

        return $json;
    }

    public function sendSmsViro($number, $otp)
    {
        $message = MasterConfiguration::where('parameter', 'sms_massage_template')->first();
        $office = CompanyProfile::first();
        $number = $this->normalizePhoneNumber($number);

        $url = env('VIRO_ENDPOINT') . '/restapi/sms/1/text/single';
        $headers = [
            'Authorization' => 'App ' . env('VIRO_API_KEY')
        ];

        $body = [
            'from' => env('VIRO_SENDER_ID'),
            'to' => "$number",
            'text' => "$otp $message->value1",
        ];

        // dd($url, $headers, $body);
        $shoot = Http::withHeaders($headers)->post($url, $body);
        // dump($shoot);
        // dump('Body : '. $shoot->body());
        // dump('Json : '. $shoot->json($key = null, $default = null));
        // dump('Status : '. $shoot->status());
        // dump('Success : '. $shoot->successful());
        // dump('Failed : '. $shoot->failed());
        // dump('Ok : ' . $shoot->ok());
        // dump('ClientError : '. $shoot->clientError());
        // dump('ServerError : '. $shoot->serverError());
        // dump('Headers : '. $shoot->header('Content-Type'));
        // dump($shoot->headers());

        $responseBody = $shoot->body();
        $covertToJson = $this->covertToJson($responseBody, true);
        $covertToJson['status'] = $shoot->status();

        return $covertToJson;
    }


    public function gMaps($location)
    {
        // https://github.com/alexpechkarev/google-maps
        $result = $this->gMaps->load('geocoding')
            ->setParam (['address' => $location])
 		    ->get();

        $covertToJson = $this->covertToJson($result, true);

        if ($covertToJson['status'] != 'OK') {
            return false;
        }

        $datas = [
            'address' => $covertToJson['results'][0]['formatted_address'],
            'longitude' => $covertToJson['results'][0]['geometry']['location']['lat'],
            'latitude' => $covertToJson['results'][0]['geometry']['location']['lng'],
        ];

        return $datas;
    }

    public function covertToJson($request, $bool = null)
    {
        if(isset($bool)){
            $jsonData = json_decode($request, $bool);
        } else {
            $jsonData = json_decode($request);
        }

        return $jsonData;
    }

    public static function normalizePhoneNumber($phoneNumber)
    {
        // Menghapus karakter selain angka
        $phoneNumber = preg_replace('/\D/', '', $phoneNumber);

        // Jika nomor telepon dimulai dengan '0'
        if (substr($phoneNumber, 0, 1) == '0') {
            // Ganti dengan '62'
            $phoneNumber = '+62' . substr($phoneNumber, 1);
        } elseif (substr($phoneNumber, 0, 1) == '+') {
            // Jika dimulai dengan '+', hapus '+' dan ganti dengan '62'
            $phoneNumber = '+62' . substr($phoneNumber, 1);
        } elseif (substr($phoneNumber, 0, 2) == '62') {
            // Jika sudah dimulai dengan '62', biarkan saja
            $phoneNumber = $phoneNumber;
        } elseif (substr($phoneNumber, 0, 3) == '008') {
            // Jika dimulai dengan '008', hapus '008' dan ganti dengan '62'
            $phoneNumber = '+62' . substr($phoneNumber, 3);
        } elseif (substr($phoneNumber, 0, 4) == '01162') {
            // Jika dimulai dengan '01162', hapus '011' dan ganti dengan '62'
            $phoneNumber = '+62' . substr($phoneNumber, 3);
        } else {
            // Default: tambahkan '62' di depan nomor telepon
            $phoneNumber = '+62' . $phoneNumber;
        }

        return $phoneNumber;
    }

    public function addTimeFromNow($modelConfig, $currentTime = null)
    {
        $config = $modelConfig->where('parameter', 't_expired_otp')->first();
        if($currentTime){
            $currentTime = Carbon::parse($currentTime);
        } else {
            $currentTime = Carbon::now();
        }

        $futureTime = null;

        if ($config->value2 == 'second') {
            $futureTime = $currentTime->addSeconds($config->value1);
        } elseif ($config->value2 == 'minute') {
            $futureTime = $currentTime->addMinutes($config->value1);
        } elseif ($config->value2 == 'hour') {
            $futureTime = $currentTime->addHours($config->value1);
        } else {
            $futureTime = $currentTime;
        }
        return $futureTime;
    }

    public function otpCodeFrom($models, $otpFrom)
    {
        if($otpFrom == 'phone') {
            $otpCode = rand(1234, 9999);
        } else if ($otpFrom == 'email'){
            $otpCode = rand(123456, 999999);
        } else {
            $otpCode = rand(1234, 9999);
        }

        $isExists = $models->select('id')->where('otp_code', $otpCode)->exists();

        if($isExists){
            self::otpCodeFrom($models, $otpFrom);
        }

        return $otpCode;
    }

    public function pushNotification1($fcm_token, $title, $message, $id = null, $type = null, $image = null)// untuk send notif ke satu user
    {
        $url = env('FIREBASE_URL') . '/fcm/send';
        $headers = [
            'Authorization:key=' . env('FIREBASE_SERVER_KEY', 'sync'),
            'Content-Type: application/json'
        ];

        $dataArr = [
            'id' => $id,
            'type' => $type,
            'click_action' => "FLUTTER_NOTIFICATION_CLICK",
            'status' => 'done'
        ];

        $notification = [
            'title' => $title,
            'body' => $message,
            'sound' => 'default',
            'image' => $image,
        ];

        $parameters = [
            'to' => $fcm_token,
            'notification' => $notification,
            'data' => $dataArr,
            'priority' => 'high'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameters));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    public function pushNotification2($FcmToken = [], $title, $body, $image = null, $type = null, $id = null, $type2 = null, $type2id = null)//untuk ke semua orang yg punya fcm token,
    {
        $url = env('FIREBASE_URL') . '/fcm/send';

        // $FcmToken = User::whereNotNull('fcm_token')->pluck('fcm_token')->all();

        $headers = [
            'Authorization:key=' . env('FIREBASE_SERVER_KEY', 'sync'),
            'Content-Type: application/json',
        ];

        $dataArr = [];
        if($type2 == null) {
            if($type == 'EVENT') {
                $dataArr = [
                    'type' => $type,
                    'event_id' => $id,
                    'click_action' => "FLUTTER_NOTIFICATION_CLICK",
                    'status' => 'done'
                ];
            }

            if($type == 'COMMUNITY') {
                $dataArr = [
                    'type' => $type,
                    'community_id' => $id,
                    'click_action' => "FLUTTER_NOTIFICATION_CLICK",
                    'status' => 'done'
                ];
            }

            if($type == 'LETSPLAY') {
                $dataArr = [
                    'type' => $type,
                    'lets_play_id' => $id,
                    'click_action' => "FLUTTER_NOTIFICATION_CLICK",
                    'status' => 'done'
                ];
            }
        } else {
            if($type == 'EVENT') {
                $dataArr = [
                    'type' => $type,
                    'event_id' => $id,
                    'slide_to' => $type2,
                    "$type2".'_id' => $type2id,
                    'click_action' => "FLUTTER_NOTIFICATION_CLICK",
                    'status' => 'done'
                ];
            }
            if($type == 'COMMUNITY') {
                $dataArr = [
                    'type' => $type,
                    'community_id' => $id,
                    'slide_to' => $type2,
                    "$type2".'_id' => $type2id,
                    'click_action' => "FLUTTER_NOTIFICATION_CLICK",
                    'status' => 'done'
                ];
            }
        }

        $notification = [
            "title" => $title,
            "body" => $body,
            'image'=> $image,
            'sound' => 'default',
            'badge' => '1'
        ];

        $arrayToSend = [
            "registration_ids" => $FcmToken,
            "notification" => $notification,
            "data" => $dataArr,
            "priority" => "high"
        ];

        $fields = json_encode ($arrayToSend);

        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        // curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POST, true );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        $result = curl_exec($ch);
        if ($result === false) {
            return curl_error($ch);
        }

        return $result;
    }

    public function pushNotification3($FcmToken, $title, $body)// untuk send notif ke satu orang (pakek package)
    {
        $title = $title;
        $body = $body;
        $message = CloudMessage::fromArray([
        'token' => $FcmToken,
        'notification' => [
            'title' => $title,
            'body' => $body
            ],
        ]);

        $this->notification->send($message);

        return true;
    }

    public static function __timeOtpExpired($parambegindate, $paramenddate)
    {
        $startDate = Carbon::parse($parambegindate);
        $endDate = Carbon::parse($paramenddate);

        $selisihDetik = $startDate->diffInSeconds($endDate);

        return $selisihDetik;
    }

    public static function removeAndCheckNewLine($text)
    {
        $textWithoutNewLine = str_replace("\n", "", $text);
        return $textWithoutNewLine;
    }

    public static function removeNullValues($data)
    {
        foreach ($data as $key => $value) {
            if ($value === null) {
                unset($data[$key]);
            }
        }

        return $data;
    }

    public static function generateCodeFromFirstLetters($columns)
    {
        $delimiters = [' ', '-', '_', '/'];
        $columns = preg_split('/[' . preg_quote(implode('', $delimiters), '/') . ']+/', $columns, -1, PREG_SPLIT_NO_EMPTY);
        $code = '';

        foreach ($columns as $codes) {
            $code .= strtoupper(substr($codes, 0, 1));
        }

        return $code;
    }

    public function generatePlayerId($column, $faculty, $len = null)
    {
        // $prefix = "DG";
        $codeFk = $this->config->where('parameter', 'm_faculty')->where('value1', $faculty)->first()->value2;
        $code = "001";

        if ($len === null) {
            $len = strlen($codeFk);
        }

        $lastData = $this->users->where($column, 'ilike', "$codeFk%")->orderByDesc('id')->first();

        if($lastData == null){
            $playerId = "$codeFk$code";
        } else {
            $lastNum = (int) substr($lastData->$column, $len);
            $num = str_pad($lastNum + 1, 3, "0", STR_PAD_LEFT);

            if ($lastNum > 99999) {
                $num = $lastNum;
            }

            $playerId = "$codeFk$num";
            // $isExists = $this->users->select('id')->where($column, $playerId)->exists();

            // if($isExists){
            //     Self::generatePlayerId($column, $faculty);
            // }
        }

        return $playerId;
    }

    public function codeVoucher($t_user_id = null)
    {
        if (!isset($t_user_id)) {
            $t_user_id = Auth::id();
        }
        $prefix = $this->users->findOrfail($t_user_id)->player_id;
        $voucher = $prefix.mt_rand(1234, 9999);

        $isExists = $this->memberEvent->where('voucher', $voucher)->exists();

        if ($isExists){
            Self::codeVoucher();
        }

        return $voucher;
    }

    public function sendWhatsappKoala($number, $otp, $otpExpired = 15)
    {
        $url = env('KOALA_URL') . '/identity/auth/koala-plus/login';

        $body = [
            'email' => env('KOALA_USERNAME'),
            'password' => env('KOALA_PASSWORD'),
        ];

        $shoot = Http::post($url, $body);

        $responseBody = $shoot->body();
        $getAuth = $this->covertToJson($responseBody, true);

    // ----------------------------------------------------------------------------------
        $number = $this->normalizePhoneNumber($number);

        $urlSend = env('KOALA_URL') . '/plus/broadcast/otp';

        $headersSend = [
            'Authorization' => 'Bearer ' . $getAuth['data']['koalaToken']['accessToken'],
            'Content-Type' => 'application/json',
            'x-kokatto-token' => 'Bearer ' . $getAuth['data']['kokattoToken']['token'],
        ];

        $bodySend = [
            'destination' => $number,
            'campaignName' => env('KOALA_CAMPAIGN_NAME'),
            'code' => $otp,
            'codeLength' => strlen($otp),
            'codeValidity' => $otpExpired,
            'type' => env('KOALA_TYPE'),
        ];

        $shoot = Http::withHeaders($headersSend)->post($urlSend, $bodySend);

        $responseBodySend = $shoot->body();
        $result = $this->covertToJson($responseBodySend, true);

        return $result;
    }

    function tanggal($parameter)
    {
        //Des 11 , 2004
        $parameter = str_replace(',', '', $parameter);

        $array = explode(" ", $parameter);
        $split1 = array_values(array_filter($array));

        $parameter1 = $split1[0]; //Des

        $bulan = [
            '1' => 'Jan',
            '2' => 'Feb',
            '3' => 'Mar',
            '4' => 'Apr',
            '5' => 'Mei',
            '6' => 'Jun',
            '7' => 'Jul',
            '8' => 'Agu',//
            '9' => 'Sep',
            '10' => 'Okt',
            '11' => 'Nov',
            '12' => 'Des',
        ];
        $hasil = array_filter($bulan, function($value) use ($parameter1) {
            return $value === $parameter1; // 12 => "Des"
        });
        $keys = array_keys($hasil); // 0 => 12
        $bulan_key = reset($keys); // 12

        $bulan2 = [
            '1' => 'Jan',
            '2' => 'Feb',
            '3' => 'Mar',
            '4' => 'Apr',
            '5' => 'May',
            '6' => 'Jun',
            '7' => 'Jul',
            '8' => 'Aug',
            '9' => 'Sep',
            '10' => 'Oct',
            '11' => 'Nov',
            '12' => 'Dec',
        ];

        $tgl = $bulan2[(int) $bulan_key]. ' ' .$split1[1]. ', ' . $split1[2]; // Dec 11, 2004

        return Carbon::parse($tgl)->format('Y-m-d'); // 2004-12-11
    }

    public function encryptDecrypt($string, $encrypt = true)
    {
        // $result = null;
        // if ($encrypt) {
        //     $hashing = date('d-m-Y') . '|' . $string;
        //     // $result = Crypt::encryptString($string);
        //     $result = base64_encode($hashing);
        // } else {
        //     // $result = Crypt::decryptString($string);
        //     $unHashing = base64_decode($string);
        //     $explode = explode('|', $unHashing);
        //     $result = $explode[1];
        // }

        // return $result;

        $method = 'AES-256-CBC';
        $secret = '0006f1b765a8469a9ad59cb19f0d8876';
        // $method = MasterReferences::select('value')->where("parameter", "used_algoritma")->first()->value;
        // $secret = MasterReferences::select('value')->where("parameter", "my_secret_key")->first()->value;

        if ($encrypt) {
            $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));
            $encrypted = openssl_encrypt($string, $method, $secret, 0, $iv);
            return base64_encode($encrypted . '::' . $iv);
        } else {
            $string = base64_decode($string);
            list($encrypted_data, $iv) = explode('::', $string, 2);
            return openssl_decrypt($encrypted_data, $method, $secret, 0, $iv);
        }
    }

    public function replaceDomain($paramConfigSearch, $paramConfigReplace, $data) {
        $search = $this->config->select('value1')->where('parameter', $paramConfigSearch)->first()->value1;
        $replace = $this->config->select('value1')->where('parameter', $paramConfigReplace)->first()->value1;
        return str_replace($search, $replace, $data);
    }
}
