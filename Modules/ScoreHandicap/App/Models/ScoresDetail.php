<?php

namespace Modules\ScoreHandicap\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Community\App\Models\Hole;
use Modules\MyGames\App\Models\Event;

class ScoresDetail extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 't_scores_detail'; 

    // Relasi ke model user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke model event
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    // Relasi ke model hole
    public function hole()
    {
        return $this->belongsTo(Hole::class, 'hole_id');
    }
}

