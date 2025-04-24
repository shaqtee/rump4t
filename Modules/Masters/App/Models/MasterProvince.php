<?php

namespace Modules\Masters\App\Models;

use App\Services\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterProvince extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = "m_provinces";
    protected $guarded = ['id'];    
}
