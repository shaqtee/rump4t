<?php

namespace Modules\Community\App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\ApiResponse;
use App\Services\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Modules\Community\App\Models\AlbumCommonity;
use Modules\Community\App\Models\Community;
use Modules\Community\App\Models\EventCommonity;
use Modules\Community\App\Models\MembersCommonity;
use Modules\Community\App\Models\PostingCommonity;
use Modules\Community\App\Models\SponsorCommonity;
use Modules\Community\App\Http\Requests\CommunityFilterRequest;
use Modules\Community\App\Services\Interfaces\CommunityInterface;

class CommunityController extends Controller
{
    protected $api;
    protected $helper;
    protected $interface;
    protected $model;
    protected $modelManage;
    protected $membersCommonity;

    public function __construct(ApiResponse $api, Helper $helper, CommunityInterface $interface, Community $model, MembersCommonity $modelManage, MembersCommonity $membersCommonity)
    {
        $this->api = $api;
        $this->helper = $helper;
        $this->interface = $interface;
        $this->model = $model;
        $this->modelManage = $modelManage;
        $this->membersCommonity = $membersCommonity;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $page = $request->size ?? 10;
            // $index = $this->model->with([
            //     'membersCommonity' => function ($query) {
            //         $query->select('name', 'image')->orderByDesc('users.id')->get();
            //     },
            // ])->filter($request)->orderBy('id', 'asc')->paginate($page);

            $index = $this->model->with(['membersCommonity'])->filter($request)->orderBy('id', 'asc')->paginate($page);

            return $this->api->list($index, $this->model);
        } catch(\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $store = $this->interface->store($this->model, $request->all());
            //save photo profile commonity
            $folder = "dgolf/community/community-profile";
            $column = "image";
            $this->helper->uploads($folder, $store, $column);

            DB::commit();
            return $this->api->success($store, "Data has been added");
        } catch(\Throwable $e) {
            DB::rollback();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        try {
            $show = $this->model->with([
                'eventCommonity' => function ($query) {
                    $query->latest()->take(5);
                },
                'postingCommonity' => function ($query) {
                    $query->latest()->take(5);
                },
                'albumCommonity' => function ($query) {
                    $query->with(['photoCommonity:id,t_album_id,name,image'])->orderByDesc('id')->take(5);
                },
                'sponsorCommonity' => function ($query) {
                    $query->with(['socialMedia'])->latest()->take(5);
                },
                'membersCommonity' => function ($query) {
                    $query->select('users.id', 'player_id', 'name', 'nickname', 'image', 'm_faculty_id')->where('t_member_community.active', 1)->with(['faculties:id,value1,value2'])->orderBy('name', 'ASC')->get();
                },
                'membersManageCommonity' => function ($query) {
                    $query->select('users.id', 'player_id', 'users.name', 'users.image', 'users.t_group_id')->where('t_member_community.active', 1)->orderBy('users.name', 'ASC')->get();
                },
            ])->find($id);

            if (!$show) {
                return $this->api->error("Data Not Found");
            }

            $show = $show->toArray();
            $show['listFacultyCommonity'] = $this->model->m_faculty();

            $coverCommunity = [
                "id"=> 1,
                "t_community_id"=> $id,
                "name"=> "cover community",
                "description" => "cover community",
                "cover"=> $show['image'],
                "photo_commonity" => [
                    [
                        "id"=> 1,
                        "t_album_id"=> 1,
                        "name"=> "cover image",
                        "image"=> $show['image']
                    ]
                ]
            ];
            array_unshift($show['album_commonity'], $coverCommunity);

            $show['leaderboards'] = $this->leaderboard($id);

            return  $this->api->success($show,  "Success to get data");
        } catch(\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $update = $this->interface->update($this->model, $request->all(), $id);
            // update photo profile commonity
            $folder = "dgolf/community/community-profile";
            $column = "image";
            $this->helper->uploads($folder, $update, $column);

            DB::commit();
            return  $this->api->success($update,  "Update Successfully");
        } catch(\Throwable $e) {
            DB::rollback();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $delete = $this->interface->destroy($this->model, $id);

            DB::commit();
            return  $this->api->delete($delete,  "Delete Successfully");
        } catch(\Throwable $e) {
            DB::rollback();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function bulk_destroy(Request $request)
    {
        DB::beginTransaction();
        try {
            $bulkDestroy = $this->interface->destroyBulk($this->model, $request->all());

            DB::commit();
            return  $this->api->delete($bulkDestroy);
        } catch(\Throwable $e) {
            DB::rollback();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function see_all($community_id, $model)
    {
        DB::beginTransaction();
        try {
            switch ($model) {
                case 'manage':
                    $seeAll = MembersCommonity::with(['members:id,t_group_id,name','members.group:id,name,description'])->where('flag_manage', '1')->where('t_community_id', $community_id)->get();
                    break;
                case 'event':
                    $seeAll = EventCommonity::where('t_community_id', $community_id)->get();
                    break;
                case 'posting':
                    $seeAll = PostingCommonity::where('t_community_id', $community_id)->get();
                    break;
                case 'album':
                    $seeAll = AlbumCommonity::with(['photoCommonity'])->where('t_community_id', $community_id)->get();
                    break;
                // case 'leaderboard':
                //     $seeAll = LeaderboardCommonity::where('t_community_id', $community_id)->get();
                //     break;
                case 'sponsor':
                    $seeAll = SponsorCommonity::with(['socialMedia'])->where('t_community_id', $community_id)->get();
                    break;
                default:
                    return $this->api->error("Invalid module type provided");
            }

            DB::commit();
            return $this->api->success($seeAll, 'Success');
        } catch(\Throwable $e) {
            DB::rollback();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            }
        }
    }

    public function join_community(Request $request)
    {
        DB::beginTransaction();
        try {
            $datas = $request->all();
            $user = User::findOrfail($request->t_user_id);
            $community = $this->model->where('id', $request->t_community_id)->exists();
            if(!$community){
                return $this->api->error("Community not found");
            }

            $memberCommunity = $this->modelManage;
            $checkJoinMember = $memberCommunity->with(['community:id,title'])->where('t_user_id', $request->t_user_id)->first();
            if($checkJoinMember){
                return $this->api->error("You already joined community ". $checkJoinMember['community']['title']);
            }

            $user->update([
                'flag_community' => 'JOINED',
                't_community_id' => $datas['t_community_id'],
            ]);

            $datas['active'] = $user->active;
            $joinCommunity = $memberCommunity->create($datas);

            if($datas['t_community_id'] !== 1 && $this->modelManage->where('t_community_id', 1)->where('t_user_id', $datas['t_user_id'])->first() == null)
            {
                $datas2 = $datas;
                $datas2['t_community_id'] = 1;
                $memberCommunity->create($datas2);
            }

            $com = $this->model->with([
                'membersCommonity' => function($q) {
                    $q->whereNotNull('users.fcm_token');
                }
            ])->find($request->t_community_id);

            $FcmToken = collect();
            foreach($com->membersCommonity as $getFcmToken) {
                $map = $getFcmToken->fcm_token;

                $FcmToken->push($map);
            }

            $this->helper->pushNotification2($FcmToken->toArray(), "Selamat Bergabung $user->name", "Jadikan Permainan Golf Sebagai Ajang Silaturahmi", 'COMMUNITY', $request->t_community_id, 'manage_people', $request->t_user_id);
            DB::commit();
            return $this->api->success($joinCommunity, "Success Joined Community ". $joinCommunity['community']['title']);
        } catch(\Throwable $e) {
            DB::rollback();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function index_manage(Request $request)
    {
        try {
            $page = $request->size ?? 10;
            $index = $this->modelManage->with(['members:id,t_group_id,name','members.group:id,name,description'])->where('flag_manage', '1')->orderByDesc('id')->get();

            return $this->api->success($index, 'Success To Get Data');
        } catch(\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function store_manage(Request $request)
    {
        DB::beginTransaction();
        try {
            $store = $this->interface->store($this->model, $request->all());

            DB::commit();
            return $this->api->success($store, "Data has been added");
        } catch(\Throwable $e) {
            DB::rollback();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function update_manage(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $request['flag_manage'] = 1;
            $update = $this->interface->update($this->modelManage, $request->all(), $id);

            DB::commit();
            return  $this->api->success($update,  "Update Successfully");
        } catch(\Throwable $e) {
            DB::rollback();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function destroy_manage($id)
    {
        DB::beginTransaction();
        try {
            $delete = $this->interface->destroy($this->modelManage, $id);

            DB::commit();
            return  $this->api->delete($delete,  "Delete Successfully");
        } catch(\Throwable $e) {
            DB::rollback();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function community_user()
    {
        try {
            $userId = Auth::user();
            $member = $this->modelManage->where('t_user_id', $userId->id)->first();
            $community = $this->model->find($member->t_community_id);

            $view = [
                'user' => $userId,
                'community' => $community,
            ];
            return $this->api->success($view, 'Success');
        } catch(\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function leaderboard($t_community_id)
    {
        try{
            $select = "users.id as t_user_id, users.name as t_user_name,
                   m_golf_course.id as m_course_id, m_golf_course.name as m_course_name, m_golf_course.number_par as m_course_num_par,
                   t_score_handicap.gross_score as gross_score";
                //    m_configurations.id as m_tee_id, m_configurations.value1 as m_tee_name,

            $data = DB::table('t_score_handicap')->select(DB::raw($select))
                        ->leftJoin('users', 't_score_handicap.t_user_id', '=', 'users.id')
                        ->leftJoin('m_golf_course', 't_score_handicap.t_course_id', '=', 'm_golf_course.id')
                        // ->leftJoin('m_configurations', 't_score_handicap.t_tee_id', '=', 'm_configurations.id')
                        // ->where('m_configurations.parameter', 'm_tee')
                        ->where('t_score_handicap.t_community_id', '=', $t_community_id)
                        ->where('users.t_community_id', '=', $t_community_id)
                        ->whereNotNull('users.t_community_id')
                        ->orderBy('users.id', 'ASC')
                        ->get();

            $collection = collect($data);
            $groupedData = $collection->groupBy('t_user_name');
            $result = $groupedData->map(function ($group) {
                $gross = $group->sum('gross_score');
                $coursePar = $group->first()->m_course_num_par;
                $toPar = $gross - $coursePar;
                $totalMatch = $group->count();

                return [
                    'name' => $group->first()->t_user_name,
                    'gross' => $gross,
                    'to_par' => $toPar,
                    'total_match' => $totalMatch,
                ];
            });

            $result = $result->sortBy(function ($item) {
                return [
                    'total_match' => -$item['total_match'],
                    'gross' => $item['gross'],
                ];
            })->values()->all();

            return  $result;
        } catch(\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function all_community(Request $request)
    {
        DB::beginTransaction();
        try {
            $index = $this->model->filter($request)->orderBy('id', 'asc')->get();
            DB::commit();
            return $this->api->success($index);
        } catch(\Throwable $e) {
            DB::rollback();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function members_community(CommunityFilterRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $membersCommonity = Arr::get($request->membersCommonity, 'filters', []);
            $byFacultyIds = Arr::get($membersCommonity, 'byFacultyId', null);
            if ($byFacultyIds) $byFacultyIds = explode(',', $byFacultyIds);

            $show = $this->model->with([
                'membersCommonity' => function ($query) use ($byFacultyIds) {
                    $query->select('users.id', 'player_id', 'name', 'nickname', 'image', 'm_faculty_id')->where('t_member_community.active', 1)->with(['faculties:id,value1,value2']);
                    if (is_array($byFacultyIds) && count($byFacultyIds) > 0) {
                        $query->whereIn('m_faculty_id', $byFacultyIds);
                    }
                    $query->orderBy('name', 'ASC');
                }
            ])->find($id);

            if (!$show || !$show->membersCommonity) {
                return $this->api->error("The community has no members");
            }

            $show = $show->membersCommonity->map(function ($member) {
                return [
                    'id' => $member->id,
                    'player_id' => $member->player_id,
                    'name' => $member->name,
                    'nickname' => $member->nickname,
                    'image' => $member->image,
                    'm_faculty_id' => $member->m_faculty_id,
                    'faculty' => $member->faculties?->value1,
                    'faculty_of_class' => $member->faculties?->value2,
                ];
            });
            DB::commit();
            return $this->api->success($show);
        } catch(\Throwable $e) {
            DB::rollback();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function template()
    {
        DB::beginTransaction();
        try {

            DB::commit();
            return $this->api->success(null, 'Template Retrieved');
        } catch(\Throwable $e) {
            DB::rollback();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }
}
