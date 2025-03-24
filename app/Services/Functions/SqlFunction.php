<?php

namespace App\Services\Functions;

use Illuminate\Support\Facades\DB;

class SqlFunction
{
    public static function view_leaderboard($t_event_id)
    {
        $select = "users.id as t_user_id, users.name as t_user_name, 
                   m_golf_course.id as m_course_id, m_golf_course.name as m_course_name, m_golf_course.number_par as m_course_num_par,
                   m_configurations.id as m_tee_id, m_configurations.value1 as m_tee_name,
                   t_score_handicap.gross_score as gross_score";

        $query = DB::table('t_score_handicap')
                    ->select(DB::raw($select))
                    ->leftJoin('users', 't_score_handicap.t_user_id', '=', 'users.id')
                    ->leftJoin('m_golf_course', 't_score_handicap.t_course_id', '=', 'm_golf_course.id')
                    ->leftJoin('m_configurations', 't_score_handicap.t_tee_id', '=', 'm_configurations.id')
                    ->where('m_configurations.parameter', 'm_tee')
                    ->where('t_score_handicap.t_event_id', '=', $t_event_id)
                    ->orderBy('users.id', 'ASC')
                    ->get();

        return $query;
    }
}