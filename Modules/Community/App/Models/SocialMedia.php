<?php

namespace Modules\Community\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Community\Database\factories\SocialMediaFactory;

class SocialMedia extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = "t_social_media";
    protected $guarded = ['id'];

    public function socialMedia(){
        return $this->belongsTo(SponsorCommonity::class, 'table_id');
    }
}
