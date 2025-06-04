<?php

namespace App\Models;

use App\Services\Helpers\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donasi extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 't_donasi';
    
    public function image_donasi()
    {
        return $this->hasMany(ImgDonasi::class, 'donasi_id');
    }

    public function donatur()
    {
        return $this->hasMany(DonaturDonasi::class, 'donasi_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeFilter($query, $request)
    {
        foreach ($request->all() as $key => $val) {
            if ($val !== null) {
                switch ($key) {
                    case 'search':
                        $query->where(function ($q) use ($val) {
                            $q->where('title', 'ilike', '%' . $val . '%')
                              ->orWhere('start_date', 'ilike', '%' . $val . '%');
                        });
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
