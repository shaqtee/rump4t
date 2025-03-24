<?php

namespace Modules\SocialMedia\App\Models;

use App\Models\User;
use Modules\SocialMedia\App\Models\DiscussionGroup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\SocialMedia\Database\factories\DiscussionMessageFactory;

class DiscussionMessage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = ['id'];
    protected $table = 't_discussion_messages';

    const CREATE_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function group(){
        return $this->belongsTo(DiscussionGroup::class, 't_group_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 't_user_id');
    }
}
