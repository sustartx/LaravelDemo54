<?php

namespace App\Http\Controllers;

use App\Events\Operation;
use App\Events\PeriodWasClosed;
use App\Events\PeriodWasOpened;
use App\Libraries\Action;
use App\Period;
use App\Score;
use App\Statistic;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;

class HomeController extends Controller
{

    public function __construct()
    {
        list($controller, $method) = explode('@', (request()->route()) ? request()->route()->getActionName() : 'action@method');
        if($method !== 'install'){
            $this->middleware('auth');
        }
    }

    public function index()
    {
        $periods_tmp = Period::all()->toArray();
        $periods_actions = [];
        $periods_statistic = [];
        $periods = [];
        array_walk($periods_tmp, function (&$value) use (&$periods, &$periods_statistic, &$periods_actions) {
            $c = Carbon::parse($value['start_date']);
            $a = $c->year . '-' . str_pad($c->month, 2, '0', STR_PAD_LEFT) . ' Period: ' . $value['period_order'];
            $b = $c->year . '-' . str_pad($c->month, 2, '0', STR_PAD_LEFT) . '---' . $value['period_order'];

            if($value['status'] == 0){
                $periods_statistic[$b] = $a;
            }else{
                $periods_actions[$b] = $a . ' (Açık)';
            }

            $periods[$b] = $a . ' (' . (($value['status']) ? 'Açık' : 'Kapalı') . ')';
        });

        $users = User::all()->pluck('name', 'id');

        $last_scores = Score::orderBy('id', 'DESC')->limit(15)->get();

        $data = [
            'periods' => $periods,
            'periods_statistic' => $periods_statistic,
            'users' => $users,
            'periods_actions' => $periods_actions,
            'last_scores' => $last_scores,
        ];
        return view('dashboard', $data);
    }

    public function install(){

        if(auth()->user()) {
            Auth::logout();
            return redirect('/login');
        }

        set_time_limit(100);

        User::truncate();
        Period::truncate();
        Score::truncate();
        Statistic::truncate();

        User::create(['name' => 'Şakir Mehmetoğlu', 'email' => 'sakir.mehmetoglu@gmail.com', 'password' => bcrypt(12345678)]);
        User::create(['name' => 'Kadir Demir', 'email' => 'kadir.demir_999@gmail.com', 'password' => bcrypt(12345678)]);
        User::create(['name' => 'Cihan Ferit', 'email' => 'cihan_ferit_999@gmail.com', 'password' => bcrypt(12345678)]);
        User::create(['name' => 'Şerif Bora', 'email' => 'serif_bora_999@gmail.com', 'password' => bcrypt(12345678)]);
        User::create(['name' => 'Ali Tutku', 'email' => 'ali_tutku_999@gmail.com', 'password' => bcrypt(12345678)]);
        User::create(['name' => 'Derya Zekeriya', 'email' => 'derya_zekeriya_999@gmail.com', 'password' => bcrypt(12345678)]);

        $users = User::all();

        $action_types = Action::getConstants();

        // Önce yıllık kayıtlar oluşturuluyor
        $years = range(2010, date('Y'));

        foreach ($years as $year) {
            // Her yıla 4 dönem giriliyor
            foreach (range(1, 4) as $period) {
                $p = new Period();
                $p->start_date = Carbon::createFromFormat('Y-m-d H:i:s', $year . '-01-01 00:00:00')->addMonth(($period - 1) * 3)->toDateTimeString();
                $p->finish_date = Carbon::createFromFormat('Y-m-d H:i:s', $year . '-00-01 23:59:59')->addMonth($period * 3)->endOfMonth()->toDateTimeString();
                $p->period_order = $period;
                $p->status = rand(0, 1);
                $p->save();

                // Her döneme en az 15 tane olacak şekilde kayıt giriliyor
                for ($i = 1; $i <= rand(15, 25); $i++){
                    // Rastgele bir kullanıcıyı belirle..
                    $rand_user_id = array_rand($users->pluck('id')->toArray());
                    $user = $users[$rand_user_id];

                    $rand_action_type = array_rand($action_types);

                    // Score hazırla..
                    $s = new Score();
                    $s->user_id = $user->id;
                    $s->period_id = $p->id;
                    $s->action_type = $rand_action_type;
                    $s->action_point = $action_types[$rand_action_type];
                    $s->score_time = Carbon::now()->toDateTimeString();
                    $s->save();
                }

                // Period kapandığında istatistikleri hesaplıyor
                if($p->status == 0){
                    event(new PeriodWasClosed($p));
                }
            }
        }


        echo 'Kurulum tamamlandı. Yönlendiriliyorsunuz.. <br />';
        echo redirect('/login');
    }

    public function do_period(){
        $period = request()->get('period_period');
        $action = request()->get('period_action');
        $operation_1 = request()->get('period___operation_1');
        $operation_2 = request()->get('period___operation_2');

        event(new Operation([
            $operation_1,
            $operation_2
        ]));

        $period = explode('---', $period);

        $p = Period::select('*')->where([
            [
                'start_date', '>=', Carbon::parse($period[0])->toDateTimeString()
            ],
            [
                'period_order', '=', $period[1]
            ]
        ])->first();

        $r = $p->update([
            'status' => $action
        ]);

        if($p->status == 0){
            event(new PeriodWasClosed($p));
        }else{
            event(new PeriodWasOpened($p));
        }

        echo json_encode(['result' => $r]);
    }

    public function get_statics(){
        $period = request()->get('statistic_period');
        $period = explode('---', $period);
        list($year, $month) = explode('-', $period[0]);
        $statistic = Statistic::where(['year' => $year, 'period' => $period[1]])->first();
        echo json_encode($statistic->statistics);
    }

    public function do_actions(){
        $user = request()->get('actions_user');
        $period = request()->get('actions_period');
        $action_type = request()->get('actions___action');

        $period = explode('---', $period);
        $action_types = Action::getConstants();

        // Score hazırla..
        $s = new Score();
        $s->user_id = $user;
        $s->period_id = $period[1];
        $s->action_type = $action_type;
        $s->action_point = $action_types[$action_type];
        $s->score_time = Carbon::now()->toDateTimeString();
        $r = $s->save();
        echo json_encode(['result' => $r]);
    }

}
