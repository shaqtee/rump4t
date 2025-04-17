<?php

namespace Modules\SocialMedia\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model , SoftDeletes};

class DetailPost extends Model
{
    use HasFactory , SoftDeletes;

    protected $guarded = ['id'];
    protected $table = 't_post_detail'; 

    // Relasi ke model user
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Relasi ke model comment
    public function post()
    {
        return $this->belongsTo(Post::class, 'id_post');
    }
}

