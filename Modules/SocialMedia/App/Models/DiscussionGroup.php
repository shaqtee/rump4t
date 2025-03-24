<?php

namespace Modules\SocialMedia\App\Models;

use App\Models\User;
use App\Services\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DiscussionGroup extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = ['id'];
    protected $table = 't_discussion_groups';

    const CREATE_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function creator(){
        return $this->belongsTo(User::class, 't_user_id');
    }

    public function members(){
        return $this->belongsToMany(User::class, 't_discussion_group_member', 't_group_id', 't_user_id');
    }
    public function messages(){
        return $this->hasMany(DiscussionMessage::class, 't_group_id');
    }

    public function scopeFilter($query, $request)
    {
        foreach ($request->all() as $key => $val) {
            if ($key === 'search' || $key === 'size' || $key === 'page') {
            } else {
                if ($request->has($key)) {
                    if ($val !== null) {
                        switch ($key) {
                            case 'name':
                                $query->where('title', 'ilike', '%' . $val . '%');
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
        return Helper::columns([
            'Name' => 'string',
        ]);
    }
}
