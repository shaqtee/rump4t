<?php

namespace Modules\Masters\App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\ApiResponse;
use Illuminate\Http\Response;
use App\Services\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\Masters\App\Models\MasterCity;
use Modules\Masters\App\Models\MasterRules;
use Modules\Masters\App\Models\MasterRegency;
use Modules\Masters\App\Models\MastersBanner;
use Modules\Masters\App\Models\MasterVillage;
use Modules\Masters\App\Models\MasterDistrict;
use Modules\Masters\App\Models\MasterProvince;
use Modules\Masters\App\Models\MasterGolfCourse;
use Modules\Masters\App\Models\MasterConfiguration;
use Modules\Masters\App\Services\Interfaces\MastersInterface;

class MastersController extends Controller
{
    protected $api;
    protected $helper;
    protected $interface;
    protected $request;

    public function __construct(ApiResponse $api, Helper $helper, MastersInterface $interface, Request $request)
    {
        $this->api = $api;
        $this->helper = $helper;
        $this->interface = $interface;
        $this->request = $request;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
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

    public function get_user()
    {
        try {
            $page = $this->request->size ?? 10;
            $datas = User::where('active', 1)->paginate($page);

            return $this->api->success($datas, 'Success Get Data');
        } catch(\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function get_faculty()
    {
        try {
            $page = $this->request->size ?? 5;
            $datas = MasterConfiguration::select('id', 'value1')->where('parameter', 'm_faculty')->filter($this->request)->paginate($page);
            return $this->api->list($datas, new MasterConfiguration, 'Success Get Data');
        } catch(\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function get_type_score()
    {
        try {
            $page = $this->request->size ?? 5;
            // $datas = MasterConfiguration::select('id', 'value1', 'value2')->where('parameter', 'm_type_scor')->filter($this->request)->paginate($page);
            $datas = MasterRules::select('id', 'name')->filter($this->request)->paginate($page);

            return $this->api->list($datas, new MasterRules(), 'Success Get Data');
        } catch(\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function get_period()
    {
        try {
            $page = $this->request->size ?? 5;
            $datas = MasterConfiguration::select('id', 'value1', 'value2')->where('parameter', 'm_period')->filter($this->request)->paginate($page);

            return $this->api->list($datas, new MasterConfiguration, 'Success Get Data');
        } catch(\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function get_city()
    {
        try {
            $page = $this->request->size ?? 5;
            $datas = MasterCity::where('is_staging', '1')->filter($this->request)->paginate($page);

            return $this->api->list($datas, new MasterCity, 'Success Get Data');
        } catch(\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function get_province()
    {
        try {
            $page = $this->request->size ?? 5;
            $datas = MasterProvince::where('is_active', '0')->filter($this->request)->paginate($page);

            return $this->api->list($datas, new MasterProvince, 'Success Get Data');
        } catch(\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function get_district()
    {
        try {
            $page = $this->request->size ?? 5;
            $datas = MasterDistrict::with('regency.province')->where('is_active', '0')->filter($this->request)->paginate($page);

            return $this->api->list($datas, new MasterDistrict, 'Success Get Data');
        } catch(\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function get_village()
    {
        try {
            $page = $this->request->size ?? 5;
            $datas = MasterVillage::with('district.regency.province')->where('is_active', '0')->filter($this->request)->paginate($page);

            return $this->api->list($datas, new MasterVillage, 'Success Get Data');
        } catch(\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function get_regency()
    {
        try {
            $page = $this->request->size ?? 5;
            $datas = MasterRegency::with(['province'])
                ->where('is_active', '0')->filter($this->request)->paginate($page);
                
            return $this->api->list($datas, new MasterRegency, 'Success Get Data');
        } catch(\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function get_course()
    {
        try {
            $page = $this->request->size ?? 150;
            $datas = MasterGolfCourse::select('id', 'name', 'address', 'latitude', 'longitude')->with(['teeCourse'])->where('is_staging', '1')->filter($this->request)->paginate($page);

            return $this->api->list($datas, new MasterGolfCourse, 'Success Get Data');
        } catch(\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function get_tee()
    {
        try {
            $page = $this->request->size ?? 5;
            $datas = MasterConfiguration::select('id', 'value1')->where('parameter', 'm_tee')->filter($this->request)->paginate($page);

            return $this->api->list($datas, new MasterConfiguration, 'Success Get Data');
        } catch(\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function get_round_type()
    {
        try {
            $page = $this->request->size ?? 5;
            $datas = MasterConfiguration::select('id', 'value1')->where('parameter', 'm_round_type')->filter($this->request)->paginate($page);

            return $this->api->list($datas, new MasterConfiguration, 'Success Get Data');
        } catch(\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function get_max_flight()
    {
        try {
            $page = $this->request->size ?? 5;
            $datas = MasterConfiguration::select('id', 'value1')->where('parameter', 'm_max_flight')->filter($this->request)->paginate($page);

            return $this->api->list($datas, new MasterConfiguration, 'Success Get Data');
        } catch(\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function banner_slide()
    {
        try {
            $datas = MastersBanner::where('on_view', true)->get();

            return $this->api->success($datas, 'Success Get Data');
        } catch(\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }
}
