@extends('layouts.admin')
@section('title', 'Редактировать фонд '.$fund->name)

@section('bredcrumb')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">@yield('title')</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin_main')}}">Админ-панель</a></li>
                    <li class="breadcrumb-item active">{{$fund->name}}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@php
    $path = 'funds';
@endphp
@section('scripts')
    <script>
        function setQualificationValue(element) {
            let value = Number(element.value);
            let input = document.getElementById('qualification_text');
            let text = document.getElementById('qualification_text_' + value).textContent;
            input.value = text;
            let block = document.getElementById('user_list');
            if (value === 0) {
                block.style.display = 'block';
            } else {
                block.style.display = 'none';
            }
            input.value = text;
        }

        function setUser(id) {
            let element = document.getElementById('user_' + id)
            element.checked = !element.checked;
            let userName = document.getElementById('user_access_' + id);
            let userQual = document.getElementById('user_qual_' + id);
            if (element.checked) {
                userName.style.color = 'green';
                userQual.style.color = 'green';
            } else {
                userName.style.color = '';
                userQual.style.color = '';
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
            let element = document.getElementById('qualification_value');
            setQualificationValue(element);
        });
    </script>
    @include('snippets.admin.editor')
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('admin_update', ['funds', $fund->id])}}" method="POST">
                        @csrf
                        <div class="form-body">
                            <h3 class="card-title">{{$fund->name}}</h3>
                            <hr>
                            <div class="row p-t-20">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Название фонда</label>
                                        <input type="text" class="form-control" name="name"
                                               placeholder="Введите название нового фонда" value="{{$fund->name}}">
                                        <small class="form-control-feedback">Введите название нового фонда</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Доступность</label>
                                        <input type="hidden" name="qualification_text" id="qualification_text">
                                        <select class="form-control custom-select" id="qualification_value"
                                                name="qualification_value" onchange="setQualificationValue(this);">
                                            <option value="1"
                                                    @php if($fund->qualification_value === 1) echo 'checked' @endphp id="qualification_text_1">
                                                Для не квалифицированных инвесторов
                                            </option>
                                            <option value="2"
                                                    @php if($fund->qualification_value === 2) echo 'checked' @endphp id="qualification_text_2">
                                                Для квалифицированных инвесторов
                                            </option>
                                            <option value="0"
                                                    @php if($fund->qualification_value === 0) echo 'checked' @endphp id="qualification_text_0">
                                                Определенным инвесторам
                                            </option>
                                        </select>
                                        <small class="form-control-feedback"> Выберите значение доступности нового
                                            фонда </small>
                                    </div>
                                </div>
                                <div class="col-md-12" id="user_list" style="display: none">
                                    <div class="form-group">
                                        <label>Выберите инвесторов которым будет доступен данный фонд</label>
                                        <div class="input-group">
                                            @foreach($users as $user)
                                                <div class="col-md-2"
                                                     style="cursor: pointer; border: solid 1px grey; border-radius: 5px; padding: 5px;"
                                                     onclick="setUser({{$user->id}})">
                                                <span id="user_access_{{$user->id}}">
                                                    <a target="_blank"
                                                       href="{{route('admin_show', ['users', $user->id])}}">{{$user->lastname}} {{$user->name}} {{$user->patronymic}}</a>
                                                </span>
                                                    <input name="access_users[]" id="user_{{$user->id}}"
                                                           value="{{$user->id}}" type="checkbox"
                                                           @if(in_array($user->id, $fund->access_users)) checked @endif >
                                                    <p id="user_qual_{{$user->id}}">
                                                        {{$user->qualification_text}}
                                                    </p>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Статус фонда</label>
                                        <select class="form-control custom-select" name="status">
                                            <option value="1" @php if($fund->status === 1) echo 'checked' @endphp >
                                                Действующий
                                            </option>
                                            <option value="2" @php if($fund->status === 2) echo 'checked' @endphp >
                                                Приостановлен
                                            </option>
                                            <option value="3" @php if($fund->status === 3) echo 'checked' @endphp >
                                                Закрыт
                                            </option>
                                        </select>
                                        <small class="form-control-feedback"> Выберите статус нового фонда </small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Параметры формирования ИК</label>
                                        <input type="number" class="form-control" value="{{$fund->omitted_min_percent}}"
                                               name="omitted_min_percent">
                                        <small class="form-control-feedback">Введите минимальное значение в
                                            процентах</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Текущее количество паёв фонда</label>
                                        <input value="{{$fund->current_count_pif}}" type="number" class="form-control"
                                               name="current_count_pif"
                                               placeholder="Введите текущее количество паёв фонда">
                                        <small class="form-control-feedback">Введите текущее количество паёв
                                            фонда</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Текущая стоимость одного пая фонда</label>
                                        <input value="{{$fund->current_cost_one_pif}}" type="number" step="any"
                                               class="form-control" name="current_cost_one_pif"
                                               placeholder="Введите текущую стоимость одного пая фонда">
                                        <small class="form-control-feedback">Введите текущую стоимость одного пая
                                            фонда</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Предыдущее количество паёв фонда</label>
                                        <input type="number" class="form-control" name="last_count_pif"
                                               placeholder="Введите предыдущее количество паёв фонда"
                                               value="{{$fund->last_count_pif}}">
                                        <small class="form-control-feedback">Введите предыдущее количество паёв
                                            фонда</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Предыдущая стоимость одного пая фонда</label>
                                        <input type="number" step="any" class="form-control" name="last_cost_one_pif"
                                               placeholder="Введите предыдущую стоимость одного пая фонда"
                                               value="{{$fund->last_cost_one_pif}}">
                                        <small class="form-control-feedback">Введите предыдущую стоимость одного пая
                                            фонда</small>
                                    </div>
                                </div>
                            </div>
                            <h3 class="box-title m-t-40">Информация</h3>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Правила фонда</label>
                                        <textarea class="form-control" name="rules"
                                                  placeholder="Введите правила фонда">{{$fund->rules}}</textarea>
                                        <small class="form-control-feedback">Введите правила фонда</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Политика фонда</label>
                                        <textarea class="form-control" name="policy"
                                                  placeholder="Введите политику фонда">{{$fund->policy}}</textarea>
                                        <small class="form-control-feedback">Введите политику фонда</small>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Предназначение фонда</label>
                                        <textarea class="form-control" name="destiny"
                                                  placeholder="Введите предназначение фонда">{{$fund->destiny}}</textarea>
                                        <small class="form-control-feedback">Введите предназначение фонда</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h3 class="box-title m-t-40">Описание</h3>
                        <hr>
                        <div class="col-md-12">
                            <textarea id="editor" name="desc">{{$fund->desc}}</textarea>
                        </div>
                        <hr>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Сохранить</button>
                            <button type="reset" class="btn btn-info"> Очистить все</button>
                            <a href="{{route('admin_index', ['funds'])}}" type="button"
                               class="btn btn-inverse">Назад</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
