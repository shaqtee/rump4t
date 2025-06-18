<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\Helpers\Helper;

class PemiluCandidate extends Model
{
    use HasFactory;
    protected $table = 't_pemilu_candidates';
    protected $guarded = ['id'];

    protected $casts = [
        'riwayat_pekerjaan' => 'array',
    ];

    public function scopeFilter($query, $request)
    {
        foreach($request->all() as $key => $val) {
            if ($key === 'search' || $key === 'size' || $key === 'page') {
            } else {
                if($request->has($key)) {
                    if($val !== null) {
                        switch ($key) {
                            case 'name' :
                                $query->where('name', 'ilike', '%'. $val .'%');
                                break;
                            case 'birth_place' :
                                $query->where('birth_place', 'ilike', '%'. $val .'%');
                                break;
                            case 'birth_date' :
                                $query->where('birth_date', 'ilike', '%'. $val .'%');
                                break;
                            case 'riwayat_pendidikan' :
                                $query->where('riwayat_pendidikan', 'ilike', '%'. $val .'%');
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

    public function columnsWeb()
    {
        return Helper::columns([
            'Name' => 'string',
        ]);
    }
}
