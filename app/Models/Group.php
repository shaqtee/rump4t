<?php

namespace App\Models;

use App\Services\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Modules\Masters\App\Models\MasterConfiguration;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends Model
{
    use HasFactory;

    protected $table = "m_groups";
    protected $guarded = ['id'];

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
                            case 'name':
                                $query->where('name',  '%' . $val . '%');
                                break;
                            case 'description':
                                $query->where('description', 'ilike', '%' . $val . '%');
                                break;
                            case 'active':
                                $query->where('active', $val);
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
        $active = MasterConfiguration::where('parameter', 'm_active')->get();

        foreach($active as $a){
            $arr[$a->value1] = $a->value2;
        }

        return Helper::columns([
            'Name' => 'string',
            'Description' => 'string',
            'Active' => $arr,
        ]);

    }
}
