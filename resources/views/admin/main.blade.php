@extends('layouts.admin')

@section('title', 'Список пользователей')

@section('bredcrumb')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">@yield('title')</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin_main')}}">Админ-панель</a></li>
                    <li class="breadcrumb-item active">Главная</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var activ = null;

        function viewUserInfo(id) {
            let block = document.getElementById('user_info_' + id);
            if (!activ) {
                activ = block;
                activ.style.display = 'block';
            } else if (activ !== block) {
                activ.style.display = 'none';
                activ = block;
                activ.style.display = '';
            } else {
                activ.style.display = 'none';
                activ = null;
            }
        }

    </script>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body p-b-0">
                    <h4 class="card-title">Пользователи</h4>
                </div>
                <div class="tab-content">
                    <div class="tab-pane active" id="home2" role="tabpanel">
                        @if(count($users) > 0)
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Ф.И.О</th>
                                    <th>Квалификация</th>
                                    <th>Количество фондов</th>
                                    <th>Стоимость портфеля</th>
                                    <th>Результаты проверок</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $user)
                                    @php
                                        $check = $user->check();
                                    @endphp
                                    <tr onclick="viewUserInfo({{$user->id}})" style="cursor: pointer">
                                        <td>
                                            <a href="{{route('admin_show', ['users', $user->id])}}">
                                                {{$user->lastname}} {{$user->name}} {{$user->patronymic}}
                                            </a>
                                            <div>
                                                Зарегистрирован : {{date('d.m.Y в H:i', strtotime((string)$user->created_at))}}
                                            </div>
                                        </td>
                                        <td>{{$user->qualification_text}}</td>
                                        <td>{{count($user->funds)}}</td>
                                        <td>{{$user->all_cost_pif()}} ₽</td>
                                        <td>
                                            @include('admin.users.checker.checkGrop')
                                        </td>
                                        <td class="text-nowrap">
                                            <a href="{{route('admin_user_check', $user->id)}}" data-toggle="tooltip"
                                               data-original-title="Перепроверить"><i class="mdi mdi-update"></i> </a>
                                            <a href="{{route('admin_edit', ['users', $user->id])}}"
                                               data-toggle="tooltip" data-original-title="Изменить"><i
                                                    class="fas fa-edit"></i> </a>
                                            <a href="{{route('admin_destroy', ['users', $user->id])}}"
                                               data-toggle="tooltip" data-original-title="Удалить в корзину"> <i
                                                    class="mdi mdi-delete-forever"></i> </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" style="padding: 0;">
                                            <div style="display: none; padding: 1rem;" id="user_info_{{$user->id}}"
                                                 onclick="viewUserInfo({{$user->id}})">
                                                <div class="row">
                                                    <div class="col-md-8" style="text-align: center;">
                                                        <h4>Данные клиента</h4>
                                                    </div>
                                                    <div class="col-md-4" style="text-align: center;">
                                                        <h4>Результаты проверок</h4>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <h4>Контакты</h4>
                                                                <div>Телефон : {{$user->phone}}</div>
                                                                <div>Email : {{$user->email}}</div>
                                                                <h4>Паспорт</h4>
                                                                <div>Серия : {{$user->passport->series ?? null}}</div>
                                                                <div>Номер : {{$user->passport->number ?? null}}</div>
                                                                <div>Кем выдан : {{$user->passport->issued_by ?? null}}</div>
                                                                <div>Когда выдан
                                                                    : {{date('d.m.Y', strtotime($user->passport->when_issued ?? null))}}</div>
                                                                <div>Код подразделения
                                                                    : {{$user->passport->division_code ?? null}}</div>
                                                            </div>
                                                            @php
                                                                $inn = $user->inn->number ?? null;
                                                                $snils = $user->snils->number ?? null;
                                                                $reg_addr = $user->address_registration()->address ?? null;
                                                                $fact_addr = $user->address_fact()->address ?? null;
                                                            @endphp
                                                            <div class="col-md-6">
                                                                <h4>Дополнительная информация</h4>
                                                                <div>Место рождения : {{$user->birth_place}}</div>
                                                                <div>Дата рождения
                                                                    : {{date('d.m.Y', strtotime($user->birth_date))}}</div>
                                                                <div>Адрес регистрации : {{$reg_addr}}</div>
                                                                <div>Адрес проживания : {{$fact_addr}}</div>
                                                                <div>ИНН : {{$inn}}</div>
                                                                <div>СНИЛС : {{$snils}}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div>Паспорт :
                                                            @include('admin.users.checker.passport')
                                                        </div>
                                                        <div>РосФинМониторинг :
                                                            @include('admin.users.checker.fedSfm')
                                                        </div>
                                                        <div>МВК :
                                                            @include('admin.users.checker.mvk')
                                                        </div>
                                                        <div>ФРОМУ :
                                                            @include('admin.users.checker.fromu')
                                                        </div>
                                                        <div>ПОД/ФТ :
                                                            @include('admin.users.checker.p639')
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{ $collection->links() }}
                        @else
                            Пользователи отсутствуют. Создайте <a href="{{route('admin_create', ['users'])}}">нового
                                пользователя</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
