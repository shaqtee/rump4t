<?php

namespace Modules\Community\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Community\Database\factories\GolfCourseFactory;

class GolfCourse extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = ['id'];
    protected $table = "m_golf_course";
    
    public function teeCourse(){
        return $this->hasMany(TeeBoxCourse::class, 't_golf_course_id');
    }

    public function holes(){
        return $this->hasMany(Hole::class, 'course_id');
    }
    
    public function scopeFilter($query, $request)
    {
        foreach ($request->all() as $key => $val) {
            if ($key === 'search' || $key === 'size' || $key === 'page') {
            } else {
                if ($request->has($key)) {
                    if ($val !== null) {
                        switch ($key) {
                            case 'name':
                                $query->where('name', 'ilike',  '%' . $val . '%');
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
}
