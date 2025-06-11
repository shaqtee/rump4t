<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Services\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use Modules\Groups\App\Models\Group as SmallGroup;
use Modules\MyGames\App\Models\Event;
use Illuminate\Notifications\Notifiable;
use Modules\MyGames\App\Models\LetsPlay;
use Modules\Masters\App\Models\MasterCity;
use Modules\Community\App\Models\Community;
use Modules\Masters\App\Models\MasterVillage;
use Modules\MyGames\App\Models\MemberLetsPlay;
use Modules\Community\App\Models\EventCommonity;
use Modules\Performace\App\Models\ScoreHandicap;
use Modules\ScoreHandicap\App\Models\LetsPlaySH;
use Modules\MyGames\App\Models\InvitationPlayers;
use Modules\Community\App\Models\MembersCommonity;
use Modules\Masters\App\Models\MasterConfiguration;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Masters\App\Models\MasterDistrict;
use Modules\Masters\App\Models\MasterProvince;
use Modules\Masters\App\Models\MasterReferences;
use Modules\Masters\App\Models\MasterRegency;

// use Modules\SocialMedia\App\Models\DiscussionGroup;
// use Modules\SocialMedia\App\Models\DiscussionGroupMember;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = [
    //     'email',
    //     'phone',
    //     'otp_code',
    //     'otp_code_login',
    //     'otp_expired',
    // ];

    protected $guarded = ['id'];
    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'password' => 'hashed',
        'reset_request' => 'boolean',
        't_community_id' => 'array',
    ];

    public function candidates()
    {
        return $this->belongsToMany(Pemilu::class, 't_pemilu_candidates', 'user_id', 't_pemilu_id')
            ->withPivot(['id', 'is_active', 'created_at', 'updated_at']);
    }

    public function small_groups()
    {
        return $this->belongsToMany(SmallGroup::class, 't_small_groups_user', 'user_id', 't_small_groups_id')
            ->withPivot(['id', 'is_admin', 'created_at', 'updated_at']);
    }

    public function group(){
        return $this->belongsTo(Group::class, 't_group_id');
    }

    public function city(){
        return $this->belongsTo(MasterCity::class, 't_city_id');
    }

    public function village(){
        return $this->belongsTo(MasterVillage::class, 'desa_kelurahan');
    }

    public function regency(){
        return $this->belongsTo(MasterRegency::class, 'kota_kabupaten');
    }

    public function district(){
        return $this->belongsTo(MasterDistrict::class, 'kecamatan');
    }

    public function province(){
        return $this->belongsTo(MasterProvince::class, 'provinsi');
    }

    // public function membersCommonity(){
    //     return $this->hasMany(MembersCommonity::class, 't_user_id');
    // }

    public function membersCommonity(){
        return $this->belongsToMany(Community::class, 't_member_community', 't_user_id', 't_community_id');
    }

    public function myEventList(){ // untuk module my games
        return $this->belongsToMany(Event::class, 't_member_event', 't_user_id', 't_event_id');
    }

    public function myEventGolfList(){ // untuk module my games
        return $this->belongsToMany(EventCommonity::class, 't_member_eventgolf', 't_user_id', 't_event_id');
    }

    public function myLetsPlayList(){ // untuk module my games
        return $this->belongsToMany(LetsPlay::class, 't_member_lets_play', 't_user_id', 't_lets_play_id');
    }

    public function myInvitedLetsPlayList(){ // untuk module my games
        return $this->belongsToMany(LetsPlay::class, 't_invitation_players', 't_user_id', 't_lets_play_id')->where('is_private', 1)->wherePivotIn('approve', ['PENDING', 'REJECTED'])->withPivot('approve');;
    }

    public function MyScore(){
        return $this->hasMany(ScoreHandicap::class, 't_user_id');
    }

    public function community(){
        return $this->belongsTo(Community::class, 't_community_id');
    }

    public function faculties(){
        return $this->belongsTo(MasterConfiguration::class, 'm_faculty_id', 'id')->where('parameter', 'm_faculty');
    }

    public function donasiDisumbangkan()
    {
        return $this->hasMany(DonaturDonasi::class, 'user_id');
    }


    // public function myGroupDiscussions(){
    //     return $this->hasMany(DiscussionGroup::class, 't_user_id');
    // }

    // public function myGroupDiscussion(){
    //     return $this->hasOne(DiscussionGroup::class, 't_user_id');
    // }

    // public function membersGroupDiscussion(){
    //     return $this->hasMany(DiscussionGroupMember::class, 't_user_id');
    // }

    // public function memberGroupDiscussion(){
    //     return $this->hasOne(DiscussionGroupMember::class, 't_user_id');
    // }

    public function scopeFilter($query, $request)
    {
        foreach($request->all() as $key => $val) {
            if ($key === 'search' || $key === 'size' || $key === 'page') {
            } else {
                if($request->has($key)) {
                    if($val !== null) {
                        switch ($key) {
                            case 'name' :
                                $query->where('name', 'ilike', '%'. $val .'%');
                                break;
                            case 'email' :
                                $query->where('email', 'ilike', '%'. $val .'%');
                                break;
                            case 'phone' :
                                $query->where('phone', 'ilike', '%'. $val .'%');
                                break;
                            case 'my_event_play_periode' :
                                $val = explode(',', $val);
                                $query->whereHas('myEventList', function($q) use($val){
                                    $q->whereIn('t_event.period', $val);
                                });
                                break;
                            case 'my_event_play_month' :
                                $val = explode(',', $val);
                                $query->whereHas('myEventList', function($q) use($val){
                                    $q->whereIn(DB::raw('EXTRACT(MONTH FROM "t_event.play_date_start")'), $val);
                                });
                                break;
                            case 'my_event_play_payment' :
                                $val = explode(',', $val);
                                $query->whereHas('myEventList', function($q) use($val){
                                    $q->whereIn('t_member_event.approve', $val);
                                });
                                break;
                            case 'my_lets_play_periode' :
                                $val = explode(',', $val);
                                $query->whereHas('myLetsPlayList', function($q) use($val){
                                    $q->whereIn('t_lets_play.periode', $val);
                                });
                                break;
                            case 'my_lets_play_month' :
                                $val = explode(',', $val);
                                $query->whereHas('myLetsPlayList', function($q) use($val){
                                    $q->whereIn(DB::raw('EXTRACT(MONTH FROM "t_lets_play.play_date")'), $val);
                                });
                                break;
                            default:
                            $query->where($key, 'ilike', '%' . $val . '%');
                            break;
                        }
                    }
                }
            }
        }
        return $query;
    }

    public function columns()
    {
        $configs = new MasterConfiguration;

        $typeScorLetsPlay = $configs->where('parameter', 'm_type_scor')->whereNot('value2', 3)->get();
        $typeScor = $configs->where('parameter', 'm_type_scor')->get();
        $period = $configs->where('parameter', 'm_period')->get();

        foreach ($typeScorLetsPlay as $tssp) {
            $tsLetsPlay[$tssp->value1] = $tssp->value2;
        }

        foreach ($typeScor as $ts) {
            $tsEvent[$ts->value1] = $ts->value2;
        }

        foreach ($period as $p) {
            $periode[$p->value1] = $p->value2;
        }

        $month = [
            "January" => 1,
            "February" => 2,
            "March" => 3,
            "April" => 4,
            "May" => 5,
            "June" => 6,
            "July" => 7,
            "August" => 8,
            "September" => 9,
            "October" => 10,
            "November" => 11,
            "December" => 12,
        ];

        $payment = [
            'WAITING FOR PAYMENT' => 'WAITING_FOR_PAYMENT',
            'PAID' => 'PAID',
            'CANCEL' => 'CANCEL',
        ];

        $myEventList = [
            'Periode',
            'Month',
            'Approve',
        ];
        $myLetsPlayList = [
            'Periode',
            'Month',
        ];
        $games = [
            'myEventList',
            'myLetsPlayList',
        ];

        return Helper::columns([
            'Nama' => 'string',
            'Email' => 'string',
            'Phone' => 'string',
            'My Event Play Periode' => $tsEvent,
            'My Event Play Month' => $month,
            'My Event Play Payment' => $payment,
            'My Lets Play Periode' => $tsLetsPlay,
            'My Lets Play Month' => $month,
        ]);
    }

    public function columnsWeb()
    {
        return Helper::columns([
            'Name' => 'string',
            'Email' => 'string',
            'Phone' => 'string',
        ]);
    }
}
