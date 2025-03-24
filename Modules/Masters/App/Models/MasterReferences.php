<?php

namespace Modules\Masters\App\Models;

use App\Traits\Blameable;
use App\Services\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Masters\Database\factories\MasterReferencesFactory;

class MasterReferences extends Model
{
    use Blameable;
    protected $table = "m_references";
    const CREATE_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'parameter',
        'code',
        'value',
        'description',
        'start',
        'end',
        'sort',
        'is_active',
        'parent_id',
        'created_by',
        'updated_by',
    ];

    public function parent()
    {
        return $this->belongsTo(MasterReferences::class, 'parent_id', 'id');
    }

    public function scopeFilter($query, $request, $parameter)
    {
        if ($request->has('search')) {
            $key = $request->search;
            $query->where('description', 'ilike', '%' . $key . '%')->orWhere('value', 'ilike', '%' . $key . '%')->orWhere('code', 'ilike', '%' . $key . '%')
                ->orWhere('mapping_old_id', 'ilike', '%' . $key . '%');
        }

        // $config = DB::select("select * from m_configurations where parameter = 'field_" . $parameter . "'");
        // if (isset($config[0])) {
        //     $config = $config[0]->value2;
        //     $config = json_decode($config, true);
        //     foreach ($request->all() as $key => $val) {
        //         if ($request->has($key)) {
        //             if ($val !== null) {
        //                 foreach ($config as $keys => $value) {
        //                     if ($key == $keys) {
        //                         $query->where($value, 'ilike', '%' . $val . '%');
        //                     }
        //                 }
        //             }
        //         }
        //     }

        //     return $query;
        // }
        
        foreach ($request->all() as $key => $val) {
            if ($key === 'search' || $key === 'size' || $key === 'page') {
            } else {
                if ($request->has($key)) {
                    if ($val !== null) {
                        switch ($key) {
                            case $parameter:
                                $query->where('parameter',  '%' . $parameter . '%');
                                break;
                            case 'code':
                                $query->where('code',  '%' . $val . '%');
                                break;
                            case 'value':
                                $query->where('value',  '%' . $val . '%');
                                break;
                            case 'description':
                                $query->where('description',  '%' . $val . '%');
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

    public static function columns($parameter)
    {
        // $config = DB::select("select * from m_configurations where parameter = 'field_" . $parameter . "'");

        // if (isset($config[0])) {
        //     $config = $config[0]->value1;
        //     $config = json_decode($config, true);
        //     return Helper::columns($config);
        // }

        return Helper::columns([
            $parameter => 'string',
            'Code' => 'string', 
            'Value' => 'string', 
            'Description' => 'string'
        ]);
    }
}
