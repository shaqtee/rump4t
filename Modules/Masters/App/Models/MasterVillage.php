<?php

namespace Modules\Masters\App\Models;

use App\Services\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterVillage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = "m_villages";
    protected $guarded = ['id'];    
}
