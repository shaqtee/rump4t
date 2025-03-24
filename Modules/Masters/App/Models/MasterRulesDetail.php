<?php

namespace Modules\Masters\App\Models;

use App\Services\Helpers\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterRulesDetail extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 't_rules_detail';

    public function masterRule()
    {
        return $this->belongsTo(MasterRules::class, 'id_rules', 'id');
    }

}
