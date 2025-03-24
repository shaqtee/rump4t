<?php

namespace Modules\SocialMedia\App\Models;

use App\Models\User;
use App\Services\Helpers\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\SocialMedia\App\Models\DetailPost;

class Post extends Model
{
    use HasFactory;

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

    public function reports()
    {
        return $this->hasMany(ReportPost::class, 't_post_id');
    }

    public function scopeFilter($query, $request)
    {
        foreach ($request->all() as $key => $val) {
            if ($key === 'search' || $key === 'size' || $key === 'page') {
            } else {
                if ($request->has($key)) {
                    if ($val !== null) {
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
            }
        }

        return $query;
    }

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

