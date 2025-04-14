<?php

namespace Modules\News\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\News\Database\factories\NewsfeedFactory;

class Newsfeed extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    
    public function getNews()
    {
        return $this->where("is_published" , true)->all();
    }
}
