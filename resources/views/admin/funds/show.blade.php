@extends('layouts.admin')


@section('bredcrumb')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">@yield('title')</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{route('admin_main')}}">Админ-панель</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <a href="{{route('admin_index', ['funds'])}}">Все фонды</a>
                    </li>
                    <li class="breadcrumb-item active">
                        {{$fund->name}}
                    </li>
                </ol>
                <a href="{{route('admin_edit', ['funds', $fund->id])}}" type="button"
                   class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i> Редактировать
                    фонд</a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{$fund->name}}</h4>
                    <div class="table-responsive">
                        <table class="table color-table primary-table">
                            <tbody>
                            <tr>
                                <td>Политика фонда</td>
                                <td>{{$fund->policy}}</td>
                            </tr>
                            <tr>
                                <td>Текущая стоимость одного пая фонда</td>
                                <td>{{$fund->current_count_pif}} </td>
                            </tr>
                            <tr>
                                <td>Текущее количество паёв фонда</td>
                                <td>{{$fund->current_cost_one_pif}}</td>
                            </tr>
                            <tr>
                                <td>Предыдущее количество паёв фонда</td>
                                <td>{{$fund->last_count_pif}}</td>
                            </tr>
                            <tr>
                                <td>Предыдущая стоимость одного пая фонда</td>
                                <td>{{$fund->last_cost_one_pif}}</td>
                            </tr>
                            <tr>
                                <td>Доступность фонда</td>
                                <td>{{$fund->qualification_text}}</td>
                            </tr>
                            <tr>
                                <td>Предназначение фонда</td>
                                <td>{{$fund->destiny}}</td>
                            </tr>
                            <tr>
                                <td>Правила фонда</td>
                                <td>{{$fund->rules}}</td>
                            </tr>
                            <tr>
                                <td>Минимальный % паев для участия в инвестиционном комитете фонда</td>
                                <td>{{$fund->omitted_min_percent}}</td>
                            </tr>
                            <tr>
                                <td>Дата обновления фонда</td>
                                <td>{{date('d.m.Y', strtotime($fund->updated_at))}}</td>
                            </tr>
                            <tr>
                                <td>Дата создания фонда</td>
                                <td>{{date('d.m.Y', strtotime($fund->created_at))}}</td>
                            </tr>
                            <tr>
                                <td>Статус фонда</td>
                                <td>
                                    @if($fund->status === 1)
                                        <span style="color: green">Действующий</span>
                                    @elseif($fund->status === 2)
                                        <span style="color: orange">Приостановлен</span>
                                    @else
                                        <span style="color: red">Закрыт</span>
                                    @endif
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="col">
                            <h4 class="card-title">Описание фонда</h4>
                            <div>
                                {{$fund->desc}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
