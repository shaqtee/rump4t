<?php

namespace Modules\SocialMedia\App\Models;

use App\Models\User;
use App\Services\Helpers\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model , SoftDeletes};
use Modules\Groups\App\Models\Group as SmallGroup;
use Modules\SocialMedia\App\Models\DetailPost;
use Illuminate\Support\Facades\Schema;
use Modules\Community\App\Models\Community;

class Post extends Model
{
    use HasFactory,SoftDeletes;


    protected $guarded = ['id'];
    protected $table = 't_post';

    // Relasi ke model user
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Relasi ke model comment
    public function comment()
    {
        return $this->hasMany(DetailPost::class, 'id_post');
    }

    public function like()
    {
        return $this->hasMany(Likes::class, 't_post_id');
    }

    public function reports()
    {
        return $this->hasMany(ReportPost::class, 't_post_id');
    }

    public function likedBy($userId)
    {
        return $this->like()->where('t_user_id', $userId)->exists();
    }

    public function postingCommonity(){
        return $this->belongsTo(Community::class, 't_community_id');
    }

    public function postingGroup(){
        return $this->belongsTo(SmallGroup::class, 't_small_groups_id');
    }

    public function scopeFilter($query, $request)
    {
        $validColumns = \Schema::getColumnListing($this->getTable());

        foreach ($request->all() as $key => $val) {
            if (in_array($key, ['search', 'size', 'page'])) {
                continue;
            }
    
            if ($val !== null && in_array($key, $validColumns)) {
                switch ($key) {
                    case 'title':
                        $query->where('title', 'ilike', '%' . $val . '%');
                        break;
                    case 'desc':
                        $query->where('desc', 'ilike', '%' . $val . '%');
                        break;
                    case 'created_at':
                        $query->whereDate('created_at', $val);
                        break;
                    default:
                        $query->where($key, 'ilike', '%' . $val . '%');
                        break;
                }
            }
        }

        return $query;
    }

    protected $casts = [
        'moderation' => 'object',
    ];

    public static function columns()
    {
        return Helper::columns([
            'Title' => 'string',
            'Description' => 'string',
            'Created At' => 'date',
        ]);
    }

    public static function columnsWeb()
    {
        return Helper::columns([
            'Title' => 'string',
            'Description' => 'string',
            'Created At' => 'date',
        ]);
    }
}

