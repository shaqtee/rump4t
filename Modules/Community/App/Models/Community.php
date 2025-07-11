<?php

namespace Modules\Community\App\Models;

use App\Models\ImgCommunity;
use App\Models\User;
use App\Traits\Blameable;
use App\Services\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Modules\Community\App\Models\MembersCommonity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Community\Database\factories\CommunityFactory;
use Modules\Masters\App\Models\MasterCity;
use Modules\Masters\App\Models\MasterConfiguration;

class Community extends Model
{
    use Blameable;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = "t_community";
    const CREATE_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $guarded = ['id'];
    protected $appends = [
        'total_members',
        'past_event',
        'ongoing_event',
        'upcoming_event',
    ];

    public function eventCommonity(){
        return $this->hasMany(EventCommonity::class, 't_community_id');
    }

    public function postingCommonity(){
        return $this->hasMany(PostingCommonity::class, 't_community_id');
    }

    public function albumCommonity(){
        return $this->hasMany(AlbumCommonity::class,'t_community_id');
    }

    public function sponsorCommonity(){
        return $this->hasMany(SponsorCommonity::class,'t_community_id');
    }

    public function city(){
        return $this->belongsTo(MasterCity::class,'t_city_id');
    }

    // public function membersCommonity(){
    //     return $this->hasMany(MembersCommonity::class,"t_community_id")->where('active', 1);
    // }

    public function membersCommonity(){
        return $this->belongsToMany(User::class, 't_member_community', 't_community_id', 't_user_id');
    }

    public function membersManageCommonity(){
        return $this->belongsToMany(User::class, 't_member_community', 't_community_id', 't_user_id')->with(['group:id,name,description'])->where('flag_manage', 1);
    }

    public function getTotalMembersAttribute(){
        return $this->membersCommonity()->count();
    }

    public function img_slider()
    {
        return $this->hasMany(ImgCommunity::class, 'komunitas_id');
    }

    public function members(){
    return $this->hasMany(MembersCommonity::class,"t_community_id");
    }

    public function getPastEventAttribute(){
        return $this->eventCommonity()->where('period', 3)->count();
    }

    public function getOngoingEventAttribute(){
        return $this->eventCommonity()->where('period', 2)->count();
    }

    public function getUpComingEventAttribute(){
        return $this->eventCommonity()->where('period', 1)->count();
    }

    public function m_faculty()
    {
        return MasterConfiguration::where('parameter', 'm_faculty')
            ->select('id', 'value1 as faculty_name', 'value2 as angkatan')
            ->get()
            ->map(function ($faculty) {
                // Ambil anggota yang terhubung dengan fakultas
                $members = User::where('faculty', $faculty->faculty_name)
                    ->select('id', 'name', 'image', 'nickname')
                    ->get()
                    ->map(function ($member) use ($faculty) {
                        $member->angkatan = $faculty->angkatan; 
                        return $member;
                    });

                // Tambahkan anggota dan jumlah anggota ke dalam data fakultas
                $faculty->member_count = $members->count();
                $faculty->members = $members;

                return $faculty;
            });
    }


    

    


    public function scopeFilter($query, $request)
    {
        // if ($request->has('search')) {
        //     $key = $request->search;
        // }

        foreach ($request->all() as $key => $val) {
            if ($key === 'search' || $key === 'size' || $key === 'page') {
            } else {
                if ($request->has($key)) {
                    if ($val !== null) {
                        switch ($key) {
                            case 'title':
                                $query->where('title', 'ilike', '%' . $val . '%');
                                break;
                            case 'description':
                                $query->where('description', 'ilike', '%' . $val . '%');
                                break;
                            case 'location':
                                $query->where('location', 'ilike', '%' . $val . '%');
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

    public static function columns()
    {
        return Helper::columns([
            'Title' => 'string',
            'Description' => 'string',
            'Location' => 'string',
        ]);
    }
}
