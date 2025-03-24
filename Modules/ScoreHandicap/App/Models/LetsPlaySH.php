<?php

namespace Modules\ScoreHandicap\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Masters\App\Models\MasterConfiguration;
use Modules\MyGames\App\Models\LetsPlay;
use Modules\ScoreHandicap\Database\factories\LetsPlaySHFactory;

class LetsPlaySH extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = ['id'];
    protected $table = "t_lets_play";
    protected $appends = [
        'type_scor',
    ];

    public function organized(){
        return $this->belongsTo(User::class, 't_user_id');
    }

    public function teeBox(){
        return $this->belongsTo(MasterConfiguration::class, 'm_tee_box_id')->where('parameter', 'm_tee');
    }

    public function roundType(){
        return $this->belongsTo(MasterConfiguration::class, 'm_round_type_id')->where('parameter', 'm_round_type');
    }

    public function getTypeScorAttribute(){
        // if ($this->m_type_scor_id == 1) {
        //     return "SYSTEM36";
        // } else if ($this->m_type_scor_id == 2) {
        //     return "STROKE PLAY";
        // } else if ($this->m_type_scor_id == 3) {
        //     return "STABLEFORD";
        // } else {
        //     return "-";
        // }

        return $this->rule ? $this->rule->name : '-';

    }
}
