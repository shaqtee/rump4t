<?php

namespace Modules\Masters\App\Models;

use App\Services\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterProvince extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = "m_provinces";
    protected $guarded = ['id'];
    protected $keyType = 'string';

    public function regency() 
    {
        return $this->hasMany(MasterRegency::class, 'province_id');
    }

    public function scopeFilter($query, $request)
    {
        // if ($request->has('search')) {
        //     $key = $request->search;
        // }

        foreach ($request->all() as $key => $val) {
            if ($key === 'search' || $key === 'size' || $key === 'page') {
            } else {
                if ($request->has($key)) {
                    if ($val !== null) {
                        switch ($key) {
                            case 'code':
                                $query->where('code', 'ilike', '%' . $val . '%');
                                break;
                            case 'name':
                                $query->where('name', 'ilike', '%' . $val . '%');
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
            'Code' => 'string',
            'Name' => 'string',
        ]);
    }
}
