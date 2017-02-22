<?php

namespace App;

use App\Libraries\Action;
use App\Listeners\PeriodWasClosedListener;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    protected $fillable = [
        'start_date',
        'finish_date',
        'period_order',
        'status',
    ];

    public $timestamps = false;

    protected $events = [
        //'saved' => PeriodWasClosedListener::class,
        //'updated' => PeriodWasClosedListener::class,
    ];

    function getPeriodOrder($start_date = null, $finish_date = null){
        if(is_null($start_date) || is_null($finish_date)){
            return 0;
        }
        return Period::select('period_order')->where([['start_date', '>=', $start_date], ['finish_date', '<=', $finish_date]])->first()->period_order ?: 0;
    }

    public function calculateStatistics(){
        $scores = Score::where(['period_id' => $this->id])->orderBy('user_id')->get()->toArray();

        // -------------------------------------------------------------------------------------------------------------
        // Best Scores
        // -------------------------------------------------------------------------------------------------------------
        $best_scores = [];
        $grouped___user = array_group($scores, 'user_id');
        foreach ($grouped___user as $user_id => $values) {
            $best_scores[$user_id] = 0;
            foreach ($values as $value) {
                $best_scores[$user_id] += $value['action_point'];
            }
        }
        // -------------------------------------------------------------------------------------------------------------

        // -------------------------------------------------------------------------------------------------------------
        // Best Action scores
        // -------------------------------------------------------------------------------------------------------------
        $best_action_scores = array_keys(Action::getConstants());
        $grouped___action_type = array_group($scores, 'action_type');
        foreach ($grouped___action_type as $action_type => $action_type_array) {
            $grouped___user = array_group($action_type_array, 'user_id');
            $grouped___user_scores = [];
            foreach ($grouped___user as $user_id => $user_id_values) {
                $grouped___user_scores[$user_id] = 0;
                foreach ($user_id_values as $value) {
                    $grouped___user_scores[$user_id] += $value['action_point'];
                }
            }
            $best_action_scores[$action_type] = $grouped___user_scores;
        }
        // -------------------------------------------------------------------------------------------------------------

        $score_result = [
            'best_score' => $this->calc_min_max($best_scores, 'max'),
            'worst_score' => $this->calc_min_max($best_scores, 'min'),
            'best_writer' => $this->calc_min_max($best_action_scores['WRITE_A_POST'], 'max'),
            'worst_writer' => $this->calc_min_max($best_action_scores['WRITE_A_POST'], 'min'),
            'best_login' => $this->calc_min_max($best_action_scores['LOGIN'], 'max'),
            'worst_login' => $this->calc_min_max($best_action_scores['LOGIN'], 'min'),
            'best_logged_in' => $this->calc_min_max($best_action_scores['VOTE_POLL'], 'max'),
            'worst_logged_in' => $this->calc_min_max($best_action_scores['VOTE_POLL'], 'min'),
        ];

        $where = [
            'year' => Carbon::parse($this->start_date)->year,
            'period' => $this->getPeriodOrder($this->start_date, $this->finish_date),
        ];
        Statistic::where($where)->delete();
        $s = new Statistic();
        $s->fill($where);
        $s->statistics = json_encode($score_result);
        $s->save();
    }

    private function calc_min_max($array, $function){
        if(is_array($array) && count($array)){
            $function_value = $function($array);
            $function_key = array_search($function_value, $array);
            return [
                'user_id' => $function_key,
                'user_name' => User::where('id', $function_key)->first()->name,
                'result' => $function_value
            ];
        }else{
            return [
                'user_id' => 0,
                'user_name' => '',
                'result' => 0
            ];
        }
    }

    public function clearStatistics(){
        $where = [
            'year' => Carbon::parse($this->start_date)->year,
            'period' => $this->getPeriodOrder($this->start_date, $this->finish_date),
        ];
        Statistic::where($where)->delete();
    }
}
