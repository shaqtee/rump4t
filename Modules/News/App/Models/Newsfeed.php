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

    protected $keyType = 'string';
    public $incrementing = false;
    

    protected $table = "t_news";
    
    public function getNews()
    {
        $news =  $this->where("is_published" , true)->orderBy("created_at" , "desc")->get();

        $news->map(function($item){
            if ($item->image) {
                $item->image = \Storage::disk('s3')->url($item->image);
            } else {
                $item->image = null;
            }
        
        });
        return $news;

    }
}
