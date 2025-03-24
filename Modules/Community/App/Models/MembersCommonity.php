<?php

namespace Modules\Community\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Community\Database\factories\MembersCommonityFactory;

class MembersCommonity extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = "t_member_community";
    protected $guarded = ['id'];

    public function community(){
        return $this->belongsTo(Community::class, 't_community_id');
    }

    public function members(){
        return $this->belongsTo(User::class, 't_user_id');
    }
}
