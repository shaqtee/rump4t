<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\Helpers\Helper;

class Pemilu extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 't_pemilu';

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function candidate_users()
    {
        return $this->hasMany(PemiluCandidate::class, 't_pemilu_id', 'id');
    }

    // public function candidate_users()
    // {
    //     return $this->belongsToMany(User::class, 't_pemilu_candidates', 't_pemilu_id', 'user_id')
    //         ->withPivot(['id','is_active','created_at']);
    // }

    public function polling_users()
    {
        return $this->belongsToMany(User::class, 't_pemilu_pollings', 't_pemilu_id', 'user_id')
            ->withPivot(['id', 't_pemilu_candidates_id' , 'vote', 'created_at']);
    }

    public function scopeFilter($query, $request)
    {
        foreach ($request->all() as $key => $val) {
            if ($key === 'search' || $key === 'size' || $key === 'page') {
                
            }else{
                if ($val !== null) {
                    switch ($key) {
                        case 'search':
                            $query->where(function ($q) use ($val) {
                                $q->where('title', 'ilike', '%' . $val . '%')
                                  ->orWhere('description', 'ilike', '%' . $val . '%')
                                  ->orWhere('is_active', 'ilike', '%' . $val . '%');
                            });
                            break;
                        default:
                            $query->where($key, 'ilike', '%' . $val . '%');
                            break;
                    }
                }
            }
        }

        return $query;
    }

    public static function columns()
    {
        return Helper::columns([
            'title' => 'string',
            'description' => 'string',
        ]);
    }

    public static function columnsPollings()
    {
        return Helper::columns([
            'title' => 'string',
        ]);
    }
}
