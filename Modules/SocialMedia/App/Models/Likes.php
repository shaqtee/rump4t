<?php

namespace Modules\SocialMedia\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\SocialMedia\Database\factories\LikesFactory;

class Likes extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = "t_likes";
    protected $primaryKey = 'id';
    public $timestamps = true;
    
    protected static function newFactory(): LikesFactory
    {
        //return LikesFactory::new();
    }
// generate ulid to id 
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = \Illuminate\Support\Str::ulid();
        });
    }
    public function uniqueLike(int $userId, int $postId): bool
    {
        // count the number of likes for the given user and post
        $count = $this->where('t_user_id', $userId)
            ->where('t_post_id', $postId)
            ->count();
            if ($count > 0) {
                return false; // like already exists
            }
            return true; // like does not exist
    }
  
}
