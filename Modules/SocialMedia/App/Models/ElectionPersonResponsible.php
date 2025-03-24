<?php

namespace Modules\SocialMedia\App\Models;

use App\Models\User;
use App\Services\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\SocialMedia\Database\factories\ElectionPersonResponsibleFactory;

class ElectionPersonResponsible extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = ['id'];
    protected $table = 't_person_responsible';

    const CREATE_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function user(){
        return $this->belongsTo(User::class, 't_user_id');
    }

    public function scopeFilter($query, $request)
    {
        foreach ($request->all() as $key => $val) {
            if ($key === 'search' || $key === 'size' || $key === 'page') {
            } else {
                if ($request->has($key)) {
                    if ($val !== null) {
                        switch ($key) {
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
        // return Helper::columns([
        //     'Name' => 'string',
        // ]);
    }

    public function columnsWeb()
    {
        // return Helper::columns([
        //     'Name' => 'string',
        // ]);
    }
}
