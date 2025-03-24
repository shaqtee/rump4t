<?php

namespace Modules\Masters\App\Models;

use App\Models\User;
use App\Services\Helpers\Helper;
use App\Traits\Blameable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Masters\Database\factories\MasterConfigurationFactory;

class MasterConfiguration extends Model
{
    use Blameable;
    protected $table = "m_configurations";
    const CREATE_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'parameter',
        'value1',
        'value2',
        'value3',
        'remark',
        'created_by',
        'updated_by',
    ];

    public function members()
    {
        return $this->hasMany(User::class, 'faculty', 'value1');
    }

    public function scopeFilter($query, $request)
    {
        if ($request->has('search')) {
            $key = $request->search;
            $query->where('parameter', 'ilike', '%' . $key . '%')->orWhere('value1', 'ilike', '%' . $key . '%')->orWhere('value2', 'ilike', '%' . $key . '%')->orWhere('value3', 'ilike', '%' . $key . '%')->orWhere('remark', 'ilike', '%' . $key . '%');
        }

        foreach ($request->all() as $key => $val) {
            if ($key === 'search' || $key === 'size' || $key === 'page') {
            } else {
                if ($request->has($key)) {
                    if ($val !== null) {
                        switch ($key) {
                            case 'parameter':
                                $query->where('parameter', 'ilike',  '%' . $val . '%');
                                break;
                            case 'value1':
                                $query->where('value1', 'ilike',  '%' . $val . '%');
                                break;
                            case 'value2':
                                $query->where('value2', 'ilike',  '%' . $val . '%');
                                break;
                            case 'value3':
                                $query->where('value3', 'ilike',  '%' . $val . '%');
                                break;
                            case 'remark':
                                $query->where('remark', 'ilike',  '%' . $val . '%');
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
            'Parameter' => 'string',
            'Value 1' => 'string',
            'Value 2' => 'string',
            'Value 3' => 'string',
            'Remark' => 'string',
        ]);
    }
}
