@extends('layouts.admin');

@section('title', 'Список фондов')

@section('bredcrumb')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">@yield('title')</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin_main')}}">Админ-панель</a></li>
                    <li class="breadcrumb-item active">Фонды</li>
                </ol>
                <a href="{{route('admin_create', ['funds'])}}" type="button"
                   class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i> Создать фонд</a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body p-b-0">
                    <h4 class="card-title">Фонды</h4>
                </div>
                <ul class="nav nav-tabs customtab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#home2" role="tab">
                            <span class="hidden-xs-down">Активные фонды</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#profile2" role="tab">
                            <span class="hidden-xs-down">Корзина</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="home2" role="tabpanel">
                        @if($active_funds->count() > 0)
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Название фонда</th>
                                    <th>Состояние</th>
                                    <th>Тип фонда (доступность)</th>
                                    <th>Количество паёв</th>
                                    <th>Текущая стоимость одного пая</th>
                                    <th>Предыдущая стоимость одного пая</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($active_funds as $fund)
                                    <tr>
                                        <td>
                                            <a href="{{route('admin_show', ['funds', $fund->id])}}">{{$fund->name}}</a>
                                        </td>
                                        <td>
                                            @if($fund->status === 1)
                                                <span style="color: green">Действующий</span>
                                            @elseif($fund->status === 2)
                                                <span style="color: orange">Приостановлен</span>
                                            @else
                                                <span style="color: red">Закрыт</span>
                                            @endif
                                        </td>
                                        <td>{{$fund->qualification_text}}</td>
                                        <td>{{$fund->current_count_pif}}</td>
                                        <td>{{$fund->current_cost_one_pif}} ₽</td>
                                        <td>{{$fund->last_cost_one_pif}} ₽</td>
                                        <td class="text-nowrap">
                                            <a href="{{route('admin_edit', ['funds', $fund->id])}}"
                                               data-toggle="tooltip" data-original-title="Изменить"><i
                                                    class="fas fa-edit"></i> </a>
                                            <a href="{{route('admin_destroy', ['funds', $fund->id])}}"
                                               data-toggle="tooltip" data-original-title="Удалить в корзину"> <i
                                                    class="mdi mdi-delete-forever"></i> </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{ $active_funds->links() }}
                        @else
                            Фонды отсутствуют. Создайте <a href="{{route('admin_create', ['funds'])}}">новый фонд</a>
                        @endif
                    </div>
                    <div class="tab-pane  p-20" id="profile2" role="tabpanel">
                        @if($delete_funds->count() > 0)
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Название фонда</th>
                                    <th>Состояние</th>
                                    <th>Тип фонда (доступность)</th>
                                    <th>Количество паёв</th>
                                    <th>Текущая стоимость одного пая</th>
                                    <th>Дата удаления</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($delete_funds as $fund)
                                    <tr>
                                        <td>
                                            <a href="{{route('admin_show', ['funds', $fund->id])}}">{{$fund->name}}</a>
                                        </td>
                                        <td>
                                            @if($fund->status === 1)
                                                <span style="color: green">Действующий</span>
                                            @elseif($fund->status === 2)
                                                <span style="color: orange">Приостановлен</span>
                                            @else
                                                <span style="color: red">Закрыт</span>
                                            @endif
                                        </td>
                                        <td>{{$fund->qualification_text}}</td>
                                        <td>{{$fund->current_count_pif}}</td>
                                        <td>{{$fund->current_cost_one_pif}} ₽</td>
                                        <td>{{date('d.m.Y', strtotime($fund->deleted_at))}} </td>
                                        <td class="text-nowrap">
                                            <a href="{{route('admin_restore', ['funds', $fund->id])}}"
                                               data-toggle="tooltip" data-original-title="Восстановить">
                                                <i class="mdi mdi-backup-restore"></i>
                                            </a>
                                            <a href="{{route('admin_delete', ['funds', $fund->id]).'#profile2'}}"
                                               data-toggle="tooltip" data-original-title="Удалить полностью">
                                                <i class="mdi mdi-delete-forever"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{ $delete_funds->links() }}
                        @else
                            Удаленные фонды отсутствуют
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
