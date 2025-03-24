<?php

namespace Modules\SocialMedia\App\Models;

use App\Services\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Elections extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = ['id'];
    protected $table = 't_elections';

    const CREATE_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function candidates(){
        return $this->hasMany(ElectionCandidates::class, 't_election_id')->orderBy('id', 'asc');
    }

    public function personResponsible(){
        return $this->hasMany(ElectionPersonResponsible::class, 't_election_id')->orderBy('id', 'asc');
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
                                $query->where('title', 'ilike', '%' . $val . '%');
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

    public function columns()
    {
        return Helper::columns([
            'Name' => 'string',
        ]);
    }

    public function columnsWeb()
    {
        return Helper::columns([
            'Name' => 'string',
        ]);
    }
}
