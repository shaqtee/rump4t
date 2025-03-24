<?php

namespace Modules\Community\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Masters\App\Models\MasterWinnerCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Community\Database\factories\WinnerCategoryEventFactory;

class WinnerCategoryEvent extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 't_winner_category_event';
    protected $guarded = ['id'];

    public function masterWinnerCategory(){
        return $this->belongsTo(MasterWinnerCategory::class, 't_winner_category_id');
    }

    public function usersWinner(){
        return $this->belongsTo(User::class, 't_user_id');
    }

    public function event(){
        return $this->belongsTo(EventCommonity::class, 't_event_id');
    }
}
