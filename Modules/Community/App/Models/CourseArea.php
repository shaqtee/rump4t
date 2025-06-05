<?php

namespace Modules\Community\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseArea extends Model
{
    use HasFactory;

    protected $table = 't_course_area';

    protected $fillable = [
        'course_id',       
        'course_name',
        'holes_number',
    ]; 

    // Relasi ke model GolfCourse
    public function golfCourse()
    {
        return $this->belongsTo(GolfCourse::class, 'course_id');
    }

}
