<?php

namespace App\Models;

use App\Services\Helpers\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonaturDonasi extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 't_donatur_donasi';

    public function donasi()
    {
        return $this->belongsTo(Donasi::class, 'donasi_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function scopeFilter($query, $request)
    {
        foreach ($request->all() as $key => $val) {
            if ($val !== null) {
                switch ($key) {
                    case 'search':
                        $query->where('name', 'ilike', '%' . $val . '%');
                        break;
                    default:
                        $query->where($key, 'ilike', '%' . $val . '%');
                        break;
                }
            }
        }

        return $query;
    }

    public static function columns()
    {
        return Helper::columns([
            'Name' => 'string',
        ]);
    }

    public static function columnsWeb()
    {
        return Helper::columns([
            'Name' => 'string',
        ]);
    }
}
