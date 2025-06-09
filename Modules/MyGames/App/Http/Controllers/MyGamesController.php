<?php

namespace Modules\MyGames\App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Helpers\Helper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Modules\MyGames\App\Models\Event;
use Modules\MyGames\App\Models\InvitationPlayers;
use Modules\MyGames\App\Models\LetsPlay;
use Modules\MyGames\App\Models\MemberEvent;
use Modules\MyGames\App\Models\MemberLetsPlay;
use Modules\MyGames\App\Models\ViewMyGamesEvent;
use Modules\MyGames\App\Models\ViewMyGamesLetsPlay;
use Modules\MyGames\App\Services\Interfaces\MyGamesInterface;

class MyGamesController extends Controller
{
    protected $api;
    protected $modelEvent;
    protected $users;
    protected $letsPlay;
    protected $interfaces;
    protected $memberMatch;
    protected $helper;
    protected $viewEvent;
    protected $viewLp;
    protected $invitationPlayers;

    public function __construct(ApiResponse $api, Event $modelEvent, User $users, LetsPlay $letsPlay, MyGamesInterface $interfaces, MemberLetsPlay $memberMatch, Helper $helper, ViewMyGamesEvent $viewEvent, ViewMyGamesLetsPlay $viewLp, InvitationPlayers $invitationPlayers)
    {
        $this->api = $api;
        $this->modelEvent = $modelEvent;
        $this->users = $users;
        $this->letsPlay = $letsPlay;
        $this->interfaces = $interfaces;
        $this->memberMatch = $memberMatch;
        $this->helper = $helper;
        $this->viewEvent = $viewEvent;
        $this->viewLp = $viewLp;
        $this->invitationPlayers = $invitationPlayers;
    }

    public function my_event_play(Request $request)
    {
        try {
            $index = $this->users->select('id', 'name')
                ->with([
                    'myEventList' => function ($q) use ($request) {
                        $q->select('t_event.id', 't_event.t_community_id', 't_event.title', 't_event.play_date_start', 't_event.play_date_end', 't_member_event.t_event_id', 't_member_event.t_user_id', 't_event.type_scoring', 't_member_event.approve')
                            ->with(['eventCommonity:id,title'])->filter($request);
                    }
                ])->findOrfail(Auth::id());

            return $this->api->list($index, $this->modelEvent);
        } catch (\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function my_lets_play(Request $request)
    {
        try {
            $userId = Auth::id();
            $index = $this->users->select('id', 'name')
                ->with([
                    'myLetsPlayList' => function ($q) use ($request) {
                        $q->with(['organized:id,name', 'roundType:id,value1'])->filter($request);
                    }
                ])
                ->findOrfail($userId);

            return $this->api->list($index, $this->letsPlay);
        } catch (\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function filter(Request $request, $typeGame)
    {
        try {
            $userId = Auth::id();
            if ($typeGame == 'event') {
                unset($request->type_game);
                $index = $this->users->select('id', 'name')
                            ->with([
                                'myEventGolfList' => function($q) use($request){
                                    $q->select('t_eventgolf.id', 't_eventgolf.t_community_id', 't_eventgolf.title', 't_eventgolf.play_date_start', 't_eventgolf.play_date_end','t_member_eventgolf.t_event_id','t_member_eventgolf.t_user_id','t_eventgolf.type_scoring','t_member_eventgolf.approve')
                                    ->with(['eventCommonity:id,title']);
                                }
                            ])->filter($request)->findOrfail($userId);
                // $index = $this->viewEvent->where('id', $userId)->filter($request)->get();
                $model = $this->users;
            }

            if ($typeGame == 'lets_play') {
                unset($request->type_game);
                $index = $this->users->select('id', 'name')
                            ->with([
                                'myLetsPlayList' => function($q) use($request){
                                    $q->with(['organized:id,name', 'roundType:id,value1', 'golfCourse']);
                                }
                            ])->filter($request)->findOrfail($userId);

                // $index = $this->viewLp->where('id', $userId)->filter($request)->get();

                // foreach ($index->myLetsPlayList as $item) {
                //     unset($item->is_private);
                // }
            
                $model = $this->viewLp;
            }

            return $this->api->list($index, $model);
        } catch (\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function my_games()
    {
        try {
            $userId = Auth::id();
            $index = $this->users->select('id', 'name')
                ->with([
                    'myEventGolfList' => function ($q) {
                        $q->select('t_eventgolf.id', 't_eventgolf.t_community_id', 't_eventgolf.title', 't_eventgolf.play_date_start', 't_eventgolf.play_date_end', 't_member_eventgolf.t_event_id', 't_member_eventgolf.t_user_id', 't_eventgolf.type_scoring', 't_member_eventgolf.approve', 't_eventgolf.m_golf_course_id')
                            ->with(['eventCommonity:id,title', 'golfCourseEvent:id,name,address']);
                    },
                    'myLetsPlayList' => function ($q) {
                        $q->with(['organized:id,name', 'roundType:id,value1', 'golfCourse:id,name,address']);
                    },
                    'myInvitedLetsPlayList'
                ])
                ->findOrfail($userId);

            // $models = [$this->letsPlay, $this->modelEvent];

            return $this->api->success($index);

            // $userId = Auth::id();
            // $index = $this->users->select('id', 'name')
            //             ->with([
            //                 'myEventList' => function($q) {
            //                     $q->select('t_event.id', 't_event.t_community_id', 't_event.title', 't_event.play_date_start', 't_event.play_date_end','t_member_event.t_event_id','t_member_event.t_user_id','t_event.type_scoring', 't_event.period','t_member_event.approve')
            //                     ->with(['eventCommonity:id,title']);
            //                 },
            //                 'myLetsPlayList' => function($q) {
            //                     $q->with(['organized:id,name', 'roundType:id,value1']);
            //                 },
            //             ])
            //             ->where('id', $userId)->first();

            // if(!$index) {
            //     return $this->api->error('Data Not Found');
            // }

            // return $this->api->list($index, $this->users);
        } catch (\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function index(Request $request)
    { //menu index lets play
        try {
            $index = $this->letsPlay->with([
                'organized:id,name',
                'golfCourse:id,name,latitude,longitude',
                'courseArea',
                'teeBox:id,tee_type,t_golf_course_id',
                'roundType:id,value1',
                'memberLetsPlay' => function ($q) {
                    $q->select('users.id', 'users.name', 'users.image', 't_member_lets_play.approve')->where('t_member_lets_play.approve', 'ACCEPT');
                }
            ])->where(function($q) {
                $q->where('is_private', '!=', 1)->orWhere('is_private', 0)->where('t_user_id', auth()->user()->id);
            })->active()->filter($request)->orderByDesc('id')->get();

            return $this->api->list($index, $this->letsPlay);
        } catch (\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $request['t_user_id'] = Auth::id();
            $request['periode'] = 1;
            $request['active'] = 1;

            $request['course_area_ids'] = json_encode($request->course_area_ids);

            $store = $this->interfaces->store($this->letsPlay, $request->all());

            $request2 = new Request([
                't_user_id' => Auth::id(),
                't_lets_play_id' => $store->id,
                'approve' => 'ACCEPT',
            ]);

            $this->join_match($request2);
            $folder = "dgolf/lets-play";
            $column = "image";
            $this->helper->uploads($folder, $store, $column);

            // if ($request->is_private !== 1) {
            //     $FcmToken = User::whereNotNull('fcm_token')->pluck('fcm_token')->all();
            //     $this->helper->pushNotification2($FcmToken, "Ayo Bergabung", Auth::user()->name . " Telah Mengundang Anda Pada $store->title", $store->image, 'LETSPLAY', $store->id);
            // }

            DB::commit();
            return $this->api->success($store, "Data has been added");
        } catch (\Throwable $e) {
            DB::rollback();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }
    public function show($id)
    {
        try {
            $show = $this->letsPlay->with([
                'organized:id,name',
                'golfCourse:id,name,latitude,longitude',
                'teeBox:id,tee_type,t_golf_course_id',
                'roundType:id,value1',
                'memberLetsPlay' => function ($q) {
                    $q->select('users.id', 'users.player_id', 'users.name', 'users.nickname', 'users.image', 't_member_lets_play.approve')->whereIn('t_member_lets_play.approve', ['ACCEPT', 'PENDING'])->orderBy('users.name', 'ASC');
                },
            ])->findOrFail($id);

            $acceptedMembers = $show->memberLetsPlay->filter(fn($member) => $member->approve == 'ACCEPT')->values()->toArray();
            $memberUser = Auth::user();
            $minOrng = 4; // 1 max flight minimal 4 orng, 4 orng dikali berapa max flight

            $show->joined = false;
            $show->user_joined = null;
            $show->approve = null;
            $show->flag_update = ($show->t_user_id == $memberUser->id);
            $show->flag_can_join = true;

            $maxFlight = $show->max_flight * $minOrng;
            $totMember = $show->memberLetsPlay->count();

            if ($totMember >= $maxFlight) {
                $show->flag_can_join = false;
            }

            foreach ($show->memberLetsPlay as $member) {
                if ($member->id === $memberUser->id) {
                    $show->joined = true;
                    $show->user_joined = [
                        "id" => $memberUser->id,
                        "name" => $memberUser->name,
                        "image" => $memberUser->image,
                    ];
                    $show->approve = $member->approve;
                    break; // Exit the loop once the matching member is found
                }
            }

            unset($show->memberLetsPlay);
            $show->member_lets_play = $acceptedMembers;

            return $this->api->success($show, "Data show");
        } catch (\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $checkOrganized = $this->letsPlay->findOrfail($id);
            $organized = Auth::id();

            if ($checkOrganized->t_user_id != $organized) {
                return $this->api->error('You Cant Edit This Game');
            }

            $request['course_area_ids'] = $request->course_area_ids;

            $update = $this->interfaces->update($this->letsPlay, $request->all(), $id);

            $folder = "dgolf/lets-play";
            $column = "image";
            $this->helper->uploads($folder, $update, $column);

            $letsPlay = $this->letsPlay->with([
                'memberLetsPlay' => function ($q) {
                    $q->where('t_member_lets_play.approve', 'ACCEPT')->whereNotNull('users.fcm_token');
                }
            ])->findOrfail($id);
            $FcmToken = collect();
            foreach ($letsPlay->memberLetsPlay as $getFcmToken) {
                $map = $getFcmToken->fcm_token;

                $FcmToken->push($map);
            }
            $this->helper->pushNotification2($FcmToken->toArray(), "Lets Play $checkOrganized->title", "Telah Di Update, Lihat Perubahan", 'LETSPLAY', $id);
            DB::commit();
            return $this->api->success($update, "Data has been updated");
        } catch (\Throwable $e) {
            DB::rollback();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function join_match(Request $request)
    {
        DB::beginTransaction();
        try {
            $datas = $request->all();
            $user = User::where('id', $request->t_user_id)->first();
            $letsPlay = $this->letsPlay->where('id', $request->t_lets_play_id)->exists();

            if (!$user) {
                return $this->api->error("User not found");
            }
            if (!$letsPlay) {
                return $this->api->error("Community not found");
            }

            $memberLetsPlay = $this->memberMatch;
            $checkJoinMatch = $memberLetsPlay->with(['letsPlay:id,title'])->where('t_user_id', $request->t_user_id)->where('t_lets_play_id', $request->t_lets_play_id)->first();
            $datas['approve'] = !empty($datas['approve']) ? $datas['approve'] : 'PENDING';

            if (isset($checkJoinMatch) && $checkJoinMatch->approve == 'PENDING') {
                return $this->api->error("You Have Made a Request To Take Part In This " . $checkJoinMatch['letsPlay']['title'] . " Let's Play");
            } else if (isset($checkJoinMatch) && $checkJoinMatch->approve == 'ACCEPT') {
                return $this->api->error("You already joined Match " . $checkJoinMatch['letsPlay']['title']);
            } else if (isset($checkJoinMatch) && ($checkJoinMatch->approve == 'CANCELED' || $checkJoinMatch->approve == 'REJECTED')) {
                $checkJoinMatch->update($datas);
                $joinEvent = $checkJoinMatch;
            } else {
                $joinEvent = $memberLetsPlay->create($datas);
            }

            $minOrng = 4;
            $letsPlay = $this->letsPlay->with([
                'memberLetsPlay' => function ($q) {
                    $q->select('users.id', 'users.name', 'users.image', 'users.fcm_token', 't_member_lets_play.approve')->where('t_member_lets_play.approve', 'ACCEPT')->whereNotNull('users.fcm_token');
                },
                'organized:id,fcm_token'
            ])->findOrfail($request->t_lets_play_id);
            $maxFlight = $letsPlay['max_flight'] * $minOrng;
            $totMember = count($letsPlay->memberLetsPlay);

            if ($totMember >= $maxFlight) {
                return $this->api->error("Let's Play Full, Max " . $letsPlay['max_flight'] . " Flight = " . $maxFlight . " Orang");
            }

            // $FcmToken = collect();
            // foreach ($letsPlay->memberLetsPlay as $getFcmToken) {
            //     $map = $getFcmToken->fcm_token;

            //     $FcmToken->push($map);
            // }
            // $this->helper->pushNotification2($FcmToken->toArray(), "Selamat Bergabung $user->name", "Jadikan $letsPlay->title Sebagai Pengalaman Bermain Yang Seru", 'LETSPLAY', $request->t_lets_play_id);
            $this->helper->pushNotification1($letsPlay->organized->fcm_token, "$user->name Meminta Bergabung", "Jadikan $letsPlay->title Sebagai Pengalaman Bermain Yang Seru", $letsPlay->id, 'LETSPLAY');
            DB::commit();
            return $this->api->success($joinEvent, "Pending Joined Event " . $joinEvent['letsPlay']['title']);
        } catch (\Throwable $e) {
            DB::rollback();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function cancel_match(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = User::where('id', $request->t_user_id)->first();
            $letsPlay = $this->letsPlay->where('id', $request->t_lets_play_id)->first();

            if (!$user) {
                return $this->api->error("User not found");
            }
            if (!$letsPlay) {
                return $this->api->error("Community not found");
            }

            if ($user->id == $letsPlay->t_user_id) {
                $cancelByorganizer = $this->memberMatch->with(['letsPlay:id,title'])->where('t_lets_play_id', $request->t_lets_play_id)->where('approve', 'ACCEPT')->get();
                $letsPlay->update([
                    'periode' => 4,
                ]);
                foreach ($cancelByorganizer as $cancel) {
                    $cancel->update([
                        'approve' => 'CANCELED',
                    ]);
                }

                $letsPlay = $this->letsPlay->with([
                    'memberLetsPlay' => function ($q) {
                        $q->where('t_member_lets_play.approve', 'ACCEPT')->whereNotNull('users.fcm_token');
                    }
                ])->findOrfail($request->t_lets_play_id);
                $FcmToken = collect();
                foreach ($letsPlay->memberLetsPlay as $getFcmToken) {
                    $map = $getFcmToken->fcm_token;

                    $FcmToken->push($map);
                }
                $this->helper->pushNotification2($FcmToken->toArray(), "Permainan Dibatalkan", "$user->name Telah Membatalkan $letsPlay->title", 'LETSPLAY', $request->t_lets_play_id);
                DB::commit();
                return $this->api->success($cancelByorganizer, "Success Canceled Event " . $letsPlay->title . " By Organizer");
            }

            $checkJoinMatch = $this->memberMatch->with(['letsPlay:id,title'])->where('t_user_id', $request->t_user_id)->where('t_lets_play_id', $request->t_lets_play_id)->where('approve', 'ACCEPT')->first();
            if (!$checkJoinMatch) {
                return $this->api->error("You Not joined This Match Or Cancel This Match");
            }

            $datas['approve'] = 'CANCELED';
            $joinEvent = $checkJoinMatch->update($datas);

            $letsPlay = $this->letsPlay->with([
                'memberLetsPlay' => function ($q) {
                    $q->where('t_member_lets_play.approve', 'ACCEPT')->whereNotNull('users.fcm_token');
                }
            ])->findOrfail($request->t_lets_play_id);
            $FcmToken = collect();
            foreach ($letsPlay->memberLetsPlay as $getFcmToken) {
                $map = $getFcmToken->fcm_token;

                $FcmToken->push($map);
            }
            $this->helper->pushNotification2($FcmToken->toArray(), "Informasi Permainan", "$user->name Batal Mengikuti $letsPlay->title", null, 'LETSPLAY', $request->t_lets_play_id);
            DB::commit();
            return $this->api->success($joinEvent, "Success Canceled Event " . $checkJoinMatch['letsPlay']['title']);
        } catch (\Throwable $e) {
            DB::rollback();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function indexMemberBeforeJoin($id)
    {
        try {
            $show = $this->letsPlay->with([
                'memberLetsPlay' => function ($q) {
                    $q->select('t_member_lets_play.id AS member_id', 'users.id AS t_user_id', 'users.player_id', 'users.name', 'users.nickname', 'users.image', 't_member_lets_play.approve')->where('t_member_lets_play.approve', 'PENDING')->orderBy('users.name', 'ASC');
                },
                'courseArea'
            ])->findOrFail($id);

            if ($show->t_user_id !== auth()->user()->id) {
                return $this->api->success([], "Data show");
            }

            if (is_array($show->course_area_ids)) {
                $orderedCourseAreas = collect($show->course_area_ids)
                    ->map(function ($id) use ($show) {
                        return $show->courseArea->firstWhere('id', (int) $id);
                    })
                    ->filter() 
                    ->values(); 
                $show->course_area = $orderedCourseAreas;
            } else {
                $show->course_area = $show->courseArea;
            }
    
            unset($show->courseArea);

            return $this->api->success($show, "Data show");
        } catch (\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function updateMemberBeforeJoin(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $request = $request->validate([
                'member_id' => 'required|integer',
                't_user_id' => 'required|integer',
                'approve' => 'required|in:ACCEPT,REJECTED',
            ]);

            $datas = [
                't_user_id' => $request['t_user_id'],
                'approve' => $request['approve'],
            ];

            $letsPlay = $this->letsPlay->select('id', 't_user_id')->findOrfail($id);
            if ($letsPlay->t_user_id !== auth()->user()->id) {
                return $this->api->error("You Are Not The Organize Of This Game");
            }

            $update = $this->interfaces->update($this->memberMatch, $datas, $request['member_id']);

            $this->helper->pushNotification1(auth()->user()->fcm_token, auth()->user()->name ." Permintaan Telah Di ". ucfirst($datas['approve']), "Jadikan $letsPlay->title Sebagai Pengalaman Bermain Yang Seru", $letsPlay->id, 'LETSPLAY');
            DB::commit();
            return $this->api->update($update, 'Success ' . ucfirst($datas['approve']) . ' Member.');
        } catch (\Throwable $e) {
            DB::rollback();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function indexPlayerSendInvitation(Request $request, $id)
    {
        try {
            $page = $request->size ?? 7;
            // $show = $this->users->select('users.id', 'users.name', 'users.nickname')
            //                     ->leftJoin('t_invitation_players', function($join) use($id) {
            //                         $join->on('users.id', '=', 't_invitation_players.t_user_id')
            //                             ->where('t_invitation_players.t_lets_play_id', $id);
            //                     })->leftJoin('t_lets_play', function($join) {
            //                         $join->on('users.id', '=', 't_lets_play.t_user_id');
            //                     })
            //                     ->whereNull('t_invitation_players.t_user_id')
            //                     ->whereNull('t_lets_play.t_user_id')
            //                     ->whereNull('users.deleted_at')
            //                     ->where('users.flag_done_profile', 1)
            //                     ->orderBy('id', 'ASC')
            //                     ->filter($request)->get();
                                // ->paginate($page);

            // $show = $this->users->select('users.id', 'users.name', 'users.nickname')->where('users.flag_done_profile', 1)
            $show = $this->users->select('users.id', 'users.name', 'users.nickname')
                                ->whereNotExists(function ($query) use ($id) {
                                    $query->select(DB::raw(1))
                                    ->from('t_invitation_players')
                                    ->whereRaw('t_invitation_players.t_user_id = users.id')
                                    ->where('t_invitation_players.t_lets_play_id', $id);
                                })->whereNotExists(function ($query) use ($id) {
                                    $query->select(DB::raw(1))
                                    ->from('t_lets_play')
                                    ->whereRaw('t_lets_play.t_user_id = users.id')
                                    ->where('t_lets_play.id', $id);
                                })->orderBy('id', 'ASC')->filter($request)->get();

            return $this->api->list($show, $this->users, "Data show");
        } catch (\Throwable $e) {
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function sendInvitingPlayers(Request $request)
    {
        DB::beginTransaction();
        try {
            foreach ($request->users as $user) {
                $store = $this->invitationPlayers->create($user);
                $user = $this->invitationPlayers->select('t_lets_play.id', 'users.name', 't_lets_play.title', 'users.fcm_token')
                                                ->join('users', function($join){
                                                    $join->on('users.id', '=', 't_invitation_players.t_user_id');
                                                })->join('t_lets_play', function($join){
                                                    $join->on('t_lets_play.id', '=', 't_invitation_players.t_lets_play_id');
                                                })->findOrfail($store->id);
                if (!empty($user->fcm_token)) {
                    $this->helper->pushNotification1($user->fcm_token,"$user->name Kamu Telah Di Undang Untuk Mengikuti $user->title", "Jadikan $user->title Sebagai Pengalaman Bermain Yang Seru", $user->id, 'LETSPLAY');
                }
            }
            DB::commit();
            return $this->api->success(null, "Users has been send invited");
        } catch (\Throwable $e) {
            DB::rollback();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }

    public function acceptInviting(Request $request)
    {
        DB::beginTransaction();
        try {
            $request['t_user_id'] = auth()->user()->id;
            if ($request->approve == "ACCEPT") {
                $store = $this->interfaces->store($this->memberMatch, $request->all());
            }
            $update = $this->invitationPlayers->where('t_user_id', $request->t_user_id)->where('t_lets_play_id', $request->t_lets_play_id)->where('approve', 'PENDING')->first();
            if (!isset($update)) {
                return $this->api->error();
            }
            $update->update([
                'approve' => $request->approve,
            ]);
            DB::commit();
            return $this->api->success($update);
        } catch (\Throwable $e) {
            DB::rollback();
            if (config('envconfig.app_debug')) {
                return $this->api->error_code($e->getMessage(), $e->getCode());
            } else {
                return $this->api->error_code_log("Internal Server Error", $e->getMessage());
            };
        }
    }
}
