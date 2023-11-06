@extends('layouts.admin')

@section('title', $user->lastname.' '.$user->name.' '.$user->patronymic)

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
                        <a href="{{route('admin_index', ['users'])}}">Все пользователи</a>
                    </li>
                    <li class="breadcrumb-item active">
                        {{$user->lastname}} {{$user->name}} {{$user->patronymic}}
                    </li>
                </ol>
                <a href="{{route('admin_edit', ['users', $user->id])}}" type="button"
                   class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i> Редактировать
                    информацию пользователя</a>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    <div class="modal fade" id="addFund" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Добавить фонд</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{route('admin_add_user_fund')}}">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="user_id" value="{{$user->id}}">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Фонды </label>
                                <select class="form-control custom-select" name="fund_id" required>
                                    @foreach($funds as $fund)
                                        @if($user->qualification_value >= $fund->qualification_value)
                                            <option value="{{$fund->id}}">{{$fund->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <small class="form-control-feedback">Выберите фонд</small>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Количество паёв</label>
                                <input type="number" class="form-control" name="count_pif"
                                       placeholder="Введите количество паёв" required>
                                <small class="form-control-feedback">Введите количество паёв</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Выход</button>
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @foreach($user->funds as  $fund)
        <div class="modal fade" id="editPifCount_{{$fund->id}}" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Изменить количество паёв</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" action="{{route('admin_edit_user_count_pif')}}">
                        <div class="modal-body">
                            @csrf
                            <input type="hidden" name="user_id" value="{{$user->id}}">
                            <input type="hidden" name="fund_id" value="{{$fund->fund->id}}">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Количество паёв</label>
                                    <input type="number" value="{{$fund->count_pif}}" class="form-control"
                                           name="count_pif" placeholder="Введите количество паёв" required>
                                    <small class="form-control-feedback">Введите количество паёв</small>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Выход</button>
                            <button type="submit" class="btn btn-primary">Сохранить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection


@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{$user->lastname}} {{$user->name}} {{$user->patronymic}}</h4>
                    <ul class="nav nav-tabs customtab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tab1" role="tab">
                                <span class="hidden-xs-down">Общая информация</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab2" role="tab">
                                <span class="hidden-xs-down">Документы</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab3" role="tab">
                                <span class="hidden-xs-down">Фонды</span>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="tab1" role="tabpanel">
                            <table class="table color-table primary-table">
                                <tbody>
                                <tr>
                                    <td>Фамилия Имя Отчество</td>
                                    <td>{{$user->lastname}} {{$user->name}} {{$user->patronymic}}</td>
                                </tr>
                                <tr>
                                    <td>Стоимость портфеля</td>
                                    <td>{{$user->all_cost_pif()}} </td>
                                </tr>
                                <tr>
                                    <td>Всего фондов</td>
                                    <td>{{count($user->funds)}} </td>
                                </tr>
                                <tr>
                                    <td>Телефон</td>
                                    <td>{{$user->phone}}</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>{{$user->email}}</td>
                                </tr>
                                <tr>
                                    <td>Квалификация</td>
                                    <td>{{$user->qualification_text}}</td>
                                </tr>
                                @php
                                    $inn = $user->inn->number ?? null;
                                    $snils = $user->snils->number ?? null;
                                    $reg_addr = null;
                                    $fact_addr = null;
                                    if(isset($user->address_registration()->address)){
                                        $reg_addr = $user->address_registration()->address;
                                    }
                                    if(isset($user->address_fact()->address)){
                                        $fact_addr = $user->address_fact()->address;
                                    }
                                @endphp
                                <tr>
                                    <td>Адрес регистрации</td>
                                    <td>{{$reg_addr}}</td>
                                </tr>
                                <tr>
                                    <td>Адрес проживания</td>
                                    <td>{{$fact_addr}}</td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-lg-12">
                                    <h4>Паспорт</h4>
                                    <table class="table">
                                        <tr>
                                            <td>
                                                Серия
                                            </td>
                                            <td>
                                                {{$user->passport->series}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Номер
                                            </td>
                                            <td>
                                                {{$user->passport->number}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Кем выдан
                                            </td>
                                            <td>
                                                {{$user->passport->issued_by}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Дата выдачи
                                            </td>
                                            <td>
                                                {{date('d.m.Y', strtotime($user->passport->when_issued))}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Код подразделения
                                            </td>
                                            <td>
                                                {{$user->passport->division_code}}
                                            </td>
                                        </tr>
                                    </table>
                                    <h4>Прочее</h4>
                                    <table class="table">
                                        <tr>
                                            <td>Снилс</td>
                                            <td>{{$snils}}</td>
                                        </tr>
                                        <tr>
                                            <td>ИНН</td>
                                            <td>{{$inn}}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab2" role="tabpanel">
                            <table class="table color-table primary-table">
                                <thead>
                                <tr>
                                    <td>Наименование</td>
                                    <td>Состояние</td>
                                    <td>Дата создания</td>
                                    <td>Дата и время подписания</td>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($user->documents as  $document)
                                    <tr>
                                        <td>
                                            <a href="{{$document->path}}" target="_blank">{{$document->name}}</a>
                                        </td>
                                        <td>
                                            @if($document->is_sign === 1 && $document->sign_status === 1)
                                                Документ подписан
                                            @elseif($document->is_sign === 1 && $document->sign_status !== 1)
                                                Документ не подписан
                                            @else
                                                Не подлежит подписи
                                            @endif
                                        </td>
                                        <td>{{date('d.m.Y', strtotime($document->created_at))}}</td>
                                        <td>{{date('d.m.Y H:i:s', strtotime($document->updated_at))}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="tab3" role="tabpanel">
                            <div class="row page-titles">
                                <div class="col-md-12 text-right">
                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#addFund">
                                            <i class="fa fa-plus-circle"></i> Добавить фонд
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <table class="table color-table primary-table">
                                <thead>
                                <tr>
                                    <td>Наименование</td>
                                    <td>Количество паев</td>
                                    <td>Стоимость портфеля</td>
                                    <td>Дата вступления</td>
                                    <td>Дата последнего приобретения</td>
                                    <td>Действия</td>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($user->funds as  $fund)
                                    <tr>
                                        <td>
                                            <a href="{{route('fund', $fund->fund->id)}}"
                                               target="_blank">{{$fund->fund->name}}</a>
                                        </td>
                                        <td>
                                            {{$fund->count_pif}}
                                        </td>
                                        <td>{{$fund->port_cost()}} ₽</td>
                                        <td>{{date('d.m.Y', strtotime($fund->updated_at))}}</td>
                                        <td>{{date('d.m.Y', strtotime($fund->created_at))}}</td>
                                        <td>
                                            <a href="" title="Изменить количество паёв" data-toggle="modal"
                                               data-target="#editPifCount_{{$fund->id}}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{route('admin_drop_user_fund', [$fund->id])}}"
                                               title="Удалить фонд">
                                                <i class="mdi mdi-delete-forever"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
