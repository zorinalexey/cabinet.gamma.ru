@extends('layouts.admin')
@section('title', 'Создать нового пользователя')
@section('scripts')
    <script>
        function setQualificationValue(element) {
            let value = element.value;
            let input = document.getElementById('qualification_text');
            input.value = document.getElementById('qualification_text_' + value).textContent;
        }
    </script>

    <script src="/js/getInn.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/suggestions-jquery@21.12.0/dist/css/suggestions.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/suggestions-jquery@21.12.0/dist/js/jquery.suggestions.min.js"></script>
    <script>
        var token = "{{$dadata['token']}}";
        var secret = "{{$dadata['secret']}}";

        $('input[name="lastname"]').suggestions({
            token: token,
            type: "NAME",
            onSelect: function(suggestion) {
                setLastName(suggestion);
            }
        });

        $('input[name="reg_addr"]').suggestions({
            token: token,
            type: "address",
            onSelect: function(suggestion) {
                var data = suggestion;
                if (!data){
                    return;
                }
                console.log(data);
                $('input[name="reg_addr"]').val(data.unrestricted_value);
            }
        });

        $('input[name="fact_addr"]').suggestions({
            token: token,
            type: "address",
            onSelect: function(suggestion) {
                var data = suggestion;
                if (!data){
                    return;
                }
                $('input[name="fact_addr"]').val(data.unrestricted_value);
            }
        });

        $('input[name="name"]').suggestions({
            token: token,
            type: "NAME",
            onSelect: function(suggestion) {
                setName(suggestion);
            }
        });

        $('input[name="patronymic"]').suggestions({
            token: token,
            type: "NAME",
            onSelect: function(suggestion) {
                setPatronymic(suggestion);
            }
        });

        $('input[name="division_code"]').suggestions({
            token: token,
            type: "fms_unit",
            onSelect: function(suggestion) {
                setDivisionParams(suggestion);
            }
        });

        $('input[name="issued_by"]').suggestions({
            token: token,
            type: "fms_unit",
            onSelect: function(suggestion) {
                setDivisionParams(suggestion);
            }
        });

        function setDivisionParams(params){
            var data = params.data;
            if (!data){
                return;
            }
            $('input[name="division_code"]').val(data.code);
            $('input[name="issued_by"]').val(data.name);

            let surname = document.getElementById('lastname').value;
            let name = document.getElementById('name').value;
            let patronymic = document.getElementById('patronymic').value;
            let birthdate = document.getElementById('birth_date').value;
            let docnumber = document.getElementById('doc_series').value + document.getElementById('doc_number').value;
            let docdate = document.getElementById('doc_when_issued').value;
            let inn = suggestInn(surname, name, patronymic, birthdate, docnumber, docdate);
            console.log(inn);

        }

        function setPatronymic(params){
            var data = params.data;
            if (!data){
                return;
            }
            $('input[name="patronymic"]').val(data.patronymic);
        }

        function setName(params){
            var data = params.data;
            if (!data){
                return;
            }
            $('input[name="name"]').val(data.name);
            if(data.patronymic){
                setPatronymic(params);
            }
        }

        function setLastName(params){
            var data = params.data;
            if (!data){
                return;
            }
            $('input[name="lastname"]').val(data.surname);
            if(data.name){
                setName(params);
            }
        }

    </script>
@endsection
@section('bredcrumb')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">@yield('title')</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin_main')}}">Админ-панель</a></li>
                    <li class="breadcrumb-item"><a href="{{route('admin_index', ['users'])}}">Пользователи</a></li>
                    <li class="breadcrumb-item active">Редактировать информацию о
                        пользователе {{$user->lastname}} {{$user->name}} {{$user->patronymic}}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection


@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('admin_update', ['users', $user->id])}}" method="POST">
                        @csrf
                        <div class="form-body">
                            <h3 class="card-title">Основная информация</h3>
                            <hr>
                            <div class="row p-t-20">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Фамилия</label>
                                        <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Введите фамилию" required value="{{$user->lastname}}">
                                        <small class="form-control-feedback">Введите фамилию</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Имя</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Введите имя" required value="{{$user->name}}">
                                        <small class="form-control-feedback">Введите имя</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Отчество</label>
                                        <input type="text" class="form-control" id="patronymic" name="patronymic" placeholder="Введите отчество (при наличии)" required value="{{$user->patronymic}}">
                                        <small class="form-control-feedback">Введите отчество (при наличии)</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Дата рождения</label>
                                        <input type="text" class="form-control" id="birth_date" name="birth_date" placeholder="Введите дату рождения" required value="{{date('d.m.Y', strtotime($user->birth_date))}}">
                                        <small class="form-control-feedback">Введите дату рождения</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Пол</label>
                                        <select class="form-control custom-select" name="gender" required>
                                            <option value="-" checked>---</option>
                                            <option value="Мужской" @if($user->gender === 'Мужской') selected @endif>
                                                Мужской
                                            </option>
                                            <option value="Женский" @if($user->gender === 'Женский') selected @endif>
                                                Женский
                                            </option>
                                        </select>
                                        <small class="form-control-feedback">Выберите пол</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Место рождения</label>
                                        <input type="text" value="{{$user->birth_place}}" class="form-control"
                                               name="birth_place" placeholder="Место рождения" required>
                                        <small class="form-control-feedback">Введите место рождения</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Роль </label>
                                        <select class="form-control custom-select" name="role" required>
                                            <option value="0">---</option>
                                            <option value="1" @if($user->role === 1) selected @endif>Клиент</option>
                                            <option value="2" @if($user->role === 2) selected @endif>Администратор
                                            </option>
                                        </select>
                                        <small class="form-control-feedback">Выберите роль</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label">Квалификация</label>
                                    <input type="hidden" name="qualification_text" id="qualification_text" required>
                                    <select class="form-control custom-select" name="qualification_value"
                                            onchange="setQualificationValue(this);" required>
                                        <option value="0">---</option>
                                        <option value="1" id="qualification_text_1"
                                                @if($user->qualification_text == 1) selected @endif>Не квалифицированный
                                            инвестор
                                        </option>
                                        <option value="2" id="qualification_text_2"
                                                @if($user->qualification_text == 2) selected @endif>Квалифицированный
                                            инвестор
                                        </option>
                                    </select>
                                    <small class="form-control-feedback"> Выберите квалификацию пользователя</small>
                                </div>
                            </div>

                            <h3 class="card-title">Контактная информация</h3>
                            <hr>
                            <div class="row p-t-20">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Телефон</label>
                                        <input type="text" class="form-control tel" name="phone"
                                               value="{{$user->phone}}" placeholder="Введите мобильный телефон"
                                               required>
                                        <small class="form-control-feedback">Введите мобильный телефон</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Email</label>
                                        <input type="email" class="form-control" name="email" value="{{$user->email}}"
                                               placeholder="Введите email">
                                        <small class="form-control-feedback">Введите email</small>
                                    </div>
                                </div>
                            </div>

                            <h3 class="card-title">Паспортные данные</h3>
                            <hr>
                            <div class="row p-t-20">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Серия паспорта</label>
                                        <input type="number" class="form-control" id="doc_series" name="series"
                                               placeholder="Введите серию паспорта" required value="{{$user->passport->series ?? null}}">
                                        <small class="form-control-feedback">Введите серию паспорта</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Номер паспорта</label>
                                        <input type="number" class="form-control" id="doc_number" name="number" placeholder="Введите номер паспорта" required value="{{$user->passport->number ?? null}}">
                                        <small class="form-control-feedback">Введите номер паспорта</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Когда выдан</label>
                                        <input type="text" class="form-control" id="doc_when_issued" name="when_issued" placeholder="Введите дату выдачи паспорта" required value="{{date('d.m.Y', strtotime($user->passport->when_issued ?? null))}}">
                                        <small class="form-control-feedback">Введите дату выдачи паспорта</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Код подразделения</label>
                                        <input type="text" class="form-control" name="division_code"
                                               placeholder="Введите код подразделения" required value="{{$user->passport->division_code ?? null}}">
                                        <small class="form-control-feedback">Введите код подразделения</small>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Кем выдан</label>
                                        <input style="width: 100%; height: 80px" type="text" class="form-control" name="issued_by"
                                               placeholder="Введите кем выдан паспорт" required value="{{$user->passport->issued_by ?? null}}">
                                        <small class="form-control-feedback">Введите кем выдан паспорт</small>
                                    </div>
                                </div>
                            </div>

                            <h3 class="card-title">Дополнительные документы</h3>
                            <hr>
                            <div class="row p-t-20">
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">ИНН</label>
                                        <input type="text" class="form-control" value="{{$inn}}" name="inn"
                                               placeholder="Введите ИНН" required>
                                        <small class="form-control-feedback">Введите ИНН</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">СНИЛС</label>
                                        <input type="text" value="{{$snils}}" class="form-control"
                                               name="snils" placeholder="Введите СНИЛС" required>
                                        <small class="form-control-feedback">Введите СНИЛС</small>
                                    </div>
                                </div>
                            </div>
                            <h3 class="card-title">Адреса</h3>
                            <hr>
                            <div class="row p-t-20">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Адрес регистрации</label>
                                        <input type="text" style="width: 100%; height: 80px" class="form-control"
                                               name="reg_addr" placeholder="Введите адрес регистрации"
                                               required value="{{$reg_addr}}">
                                        <small class="form-control-feedback">Введите адрес регистрации</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Адрес проживания</label>
                                        <input style="width: 100%; height: 80px" type="text" class="form-control"
                                               name="fact_addr" placeholder="Введите адрес проживания"
                                               required value="{{$fact_addr}}">
                                        <small class="form-control-feedback">Введите адрес проживания</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Сохранить</button>
                            <button type="reset" class="btn btn-info"> Очистить все</button>
                            <a href="{{route('admin_index', ['users'])}}" type="button"
                               class="btn btn-inverse">Назад</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
