@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">Dönem İşlemleri</div>
                <div class="panel-body">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label for="period_period" class="col-md-4 control-label">Dönem</label>
                            <div class="col-md-8">
                                {!! Form::select('period_period', $periods, null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="period_action" class="col-md-4 control-label">İşlem</label>
                            <div class="col-md-8">
                                <select name="period_action" id="period_action" class="form-control">
                                    <option value="1">Aç</option>
                                    <option value="0">Kapat</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="period___operation_1" class="col-md-4 control-label">Operasyon 1</label>
                            <div class="col-md-8">
                                <select name="period___operation_1" id="period___operation_1" class="form-control">
                                    <option value="">Seçiniz</option>
                                    <option value="SendMail">Mail Gönder</option>
                                    <option value="StartBackup">Yedek Al</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="period___operation_2" class="col-md-4 control-label">Operasyon 2</label>
                            <div class="col-md-8">
                                <select name="period___operation_2" id="period___operation_2" class="form-control">
                                    <option value="">Seçiniz</option>
                                    <option value="SendMail">Mail Gönder</option>
                                    <option value="StartBackup">Yedek Al</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <button type="button" id="do_period_action" class="btn btn-primary col-md-12">İşlem Yap</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">Demo İşlemler</div>
                <div class="panel-body">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label for="actions_user" class="col-md-4 control-label">Üye</label>
                            <div class="col-md-8">
                                {!! Form::select('actions_user', $users, null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="actions_period" class="col-md-4 control-label">Dönem</label>
                            <div class="col-md-8">
                                {!! Form::select('actions_period', $periods_actions, null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="actions___action" class="col-md-4 control-label">İşlem</label>
                            <div class="col-md-8">
                                <select name="actions___action" id="actions___action" class="form-control">
                                    <option value="LOGIN">Giriş Puanı Ver</option>
                                    <option value="WRITE_A_POST">Yazı Puanı Ver</option>
                                    <option value="VOTE_POLL">Anket Puanı Ver</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <button type="button" id="do_actions" class="btn btn-primary col-md-12">İşlem Yap</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">İstatistikler</div>
                <div class="panel-body">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label for="statistic_period" class="col-md-4 control-label">Dönem</label>
                            <div class="col-md-8">
                                {!! Form::select('statistic_period', $periods_statistic, null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <button type="button" id="get_statics" class="btn btn-primary col-md-12">Göster</button>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="statistics"></div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Son İşlemler</div>
                <div class="panel-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>İşlem</th>
                                <th>Puan</th>
                                <th>Zaman</th>
                                <th>Üye</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($last_scores as $score)
                                <tr>
                                    <td>{!! $score['action_type'] !!}</td>
                                    <td>{!! $score['action_point'] !!}</td>
                                    <td>{!! $score['score_time'] !!}</td>
                                    <td>{!! $score->user->name !!}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script type="text/javascript">
        $(function () {
            $('#do_period_action').on('click', function () {
                var period_period = $('select[name=period_period] option:selected').val();
                var period_action = $('select[name=period_action] option:selected').val();
                var period___operation_1 = $('select[name=period___operation_1] option:selected').val();
                var period___operation_2 = $('select[name=period___operation_2] option:selected').val();
                var data = {
                    'period_period': period_period,
                    'period_action': period_action,
                    'period___operation_1': period___operation_1,
                    'period___operation_2': period___operation_2,
                    '_token': '{!! csrf_token() !!}'
                };
                $.ajax({
                    type: 'POST',
                    url: '{!! route('do_period') !!}',
                    data: data,
                    dataType: 'json',
                    success: function (result) {
                        location.reload();
                    }
                });
                return false;
            });
            $('#get_statics').on('click', function () {
                var statistic_period = $('select[name=statistic_period] option:selected').val();
                var data = {
                   'statistic_period' : statistic_period,
                    '_token': '{!! csrf_token() !!}'
                };
                $.ajax({
                    type: 'POST',
                    url: '{!! route('get_statics') !!}',
                    data: data,
                    dataType: 'json',
                    success: function (result) {
                        var r = JSON.parse(result);
                        $('#statistics').html(html);
                        var html = '<ul>' +
                            '           <li>En Yüksek Puan (Genel) : <strong>' + r.best_score.user_name + ' (' + r.best_score.result + ')</strong></li>' +
                            '           <li>En Düşük Puan (Genel) : <strong>' + r.worst_score.user_name + ' (' + r.worst_score.result + ')</strong></li>' +
                            '           <li>En Yüksek Puan (Yazı) : <strong>' + r.best_writer.user_name + ' (' + r.best_writer.result + ')</strong></li>' +
                            '           <li>En Düşük Puan (Yazı) : <strong>' + r.worst_writer.user_name + ' (' + r.worst_writer.result + ')</strong></li>' +
                            '           <li>En Yüksek Puan (Giriş) : <strong>' + r.best_login.user_name + ' (' + r.best_login.result + ')</strong></li>' +
                            '           <li>En Düşük Puan (Giriş) : <strong>' + r.worst_login.user_name + ' (' + r.worst_login.result + ')</strong></li>' +
                            '           <li>En Yüksek Puan (Anket) : <strong>' + r.best_logged_in.user_name + ' (' + r.best_logged_in.result + ')</strong></li>' +
                            '           <li>En Düşük Puan (Anket) : <strong>' + r.worst_logged_in.user_name + ' (' + r.worst_logged_in.result + ')</strong></li>' +
                            '       </ul>';
                        $('#statistics').html(html);
                    }
                });
                return false;
            });
            $('#do_actions').on('click', function () {
                var actions_user = $('select[name=actions_user] option:selected').val();
                var actions_period = $('select[name=actions_period] option:selected').val();
                var actions___action = $('select[name=actions___action] option:selected').val();

                var data = {
                    'actions_user': actions_user,
                    'actions_period': actions_period,
                    'actions___action': actions___action,
                    '_token': '{!! csrf_token() !!}'
                };
                $.ajax({
                    type: 'POST',
                    url: '{!! route('do_actions') !!}',
                    data: data,
                    dataType: 'json',
                    success: function (result) {
                        if(result.result){
                            location.reload();
                        }else{
                            alert('Kayıt Başarısız Oldu !');
                        }
                    }
                });
                return false;
            });
        });
    </script>
@endsection