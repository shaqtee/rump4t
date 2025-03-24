<?php

namespace Modules\SocialMedia\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\SocialMedia\Database\factories\ReportPostFactory;

class ReportPost extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 't_report_post';

    public function user(){
        return $this->belongsTo(User::class, 't_user_id');
    }

    public function post(){
        return $this->hasMany(Post::class, 't_post_id');
    }
}
