<?php

namespace Modules\Community\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\ScoreHandicap\App\Models\ScoresDetail;

class Hole extends Model
{
    use HasFactory;

    protected $table = 't_holes'; 

    protected $fillable = [
        'course_id',
        'hole_number',
        'par',
    ];

    // Relasi ke model GolfCourse
    public function golfCourse()
    {
        return $this->belongsTo(GolfCourse::class, 'course_id');
    }

    public function scoreDetail()
    {
        return $this->hasOne(ScoresDetail::class, 'hole_id');
    }    
}
