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
}
