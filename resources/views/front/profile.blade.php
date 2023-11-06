@extends('layouts.cabinet')
@section('title', $title)

@php
    $user = \Illuminate\Support\Facades\Auth::user();
@endphp

@section('modal-windows')
    @include('snippets.modal_windows_currency_account')
    @include('snippets.modal_windows_ruble_account')

    <div id="editEmail" class="popup-wrapper">
        <form class="modal-body" method="post" action="{{route('edit_email')}}">
            @csrf
            <div class="form-block">
                <input type="email" class="input-default" name="email" placeholder="Введите новый email">
            </div>
            <div class="form-block center">
                <button class="btn-green">Сохранить</button>
            </div>
        </form>
    </div>

    <div id="editPhone" class="popup-wrapper">
        <form class="modal-body" method="post" action="{{route('edit_phone')}}">
            @csrf
            <div class="form-block">
                <input type="text" class="tel input-default" name="phone" placeholder="Введите новый номер телефона">
            </div>
            <div class="form-block center">
                <button class="btn-green">Сохранить</button>
            </div>
        </form>
    </div>
    <script src="/js/tabs.js"></script>
    <script src="/js/popup.js"></script>
    <script src="/js/select.js"></script>
@endsection


@section('content')
    <div class="content-body">
        <div class="row">
            <div class="breadcrumbs">
                <div class="breadcrumbs-item">
                    <a href="{{route('cabinet')}}">Личный кабинет</a>
                </div>
                <div class="separator">|</div>
                <div class="breadcrumbs-item">{{$title}}</div>
            </div>
        </div>
        <div class="row m-0">
            <div class="tabs">
                <div id="1" class="tab-item active">Мои данные</div>
                <div id="2" class="tab-item">Анкета</div>
                <div id="3" class="tab-item">Банковские реквизиты</div>
            </div>
        </div>
        <div class="row m-0">
            <div id="tab-content1" class="tab-content active">
                <div class="data-wrapper">
                    <div class="data-table">
                        <div class="row">
                            <div class="data-item">
                                <div class="data-item__name">ФИО</div>
                            </div>
                            <div class="data-item">
                                <div
                                    class="data-item__value">{{$user->lastname}} {{$user->name}} {{$user->patronymic}}</div>
                            </div>
                        </div>
                        <div class="row m-0">
                            <div class="data-item">
                                <div class="data-item__name">E-mail</div>
                            </div>
                            <div class="data-item">
                                <div class="data-item__value">{{$user->email}}</div>
                            </div>
                        </div>
                        <div class="row m-0">
                            <div class="data-item">
                                <div class="data-item__name">Телефон</div>
                            </div>
                            <div class="data-item">
                                <div class="data-item__value">{{$user->phone}}</div>
                            </div>
                        </div>
                        <div class="row m-0">
                            <div class="data-item">
                                <div class="data-item__name">Пол</div>
                            </div>
                            <div class="data-item">
                                <div class="data-item__value">{{$user->gender}}</div>
                            </div>
                        </div>
                        <div class="row m-0">
                            <div class="data-item">
                                <div class="data-item__name">Дата рождения</div>
                            </div>
                            <div class="data-item">
                                <div class="data-item__value">{{date('d.m.Y', strtotime($user->birth_date))}}</div>
                            </div>
                        </div>
                        <div class="row m-0">
                            <div class="data-item">
                                <div class="data-item__name">Место рождения</div>
                            </div>
                            <div class="data-item">
                                <div class="data-item__value">{{$user->birth_place}}</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="data-item">
                                <div class="data-item__name">Документ</div>
                            </div>
                            <div class="data-item">
                                <div class="data-item__value">Паспорт РФ</div>
                            </div>
                        </div>
                        <div class="row m-0">
                            <div class="data-item">
                                <div class="data-item__name">Гражданство</div>
                            </div>
                            <div class="data-item">
                                <div class="data-item__value">Российская Федерация</div>
                            </div>
                        </div>
                        <div class="row m-0">
                            <div class="data-item">
                                <div class="data-item__name">Серия документа</div>
                            </div>
                            <div class="data-item">
                                <div class="data-item__value">{{$user->passport->series}}</div>
                            </div>
                        </div>
                        <div class="row m-0">
                            <div class="data-item">
                                <div class="data-item__name">Номер документа</div>
                            </div>
                            <div class="data-item">
                                <div class="data-item__value">{{$user->passport->number}}</div>
                            </div>
                        </div>
                        <div class="row m-0">
                            <div class="data-item">
                                <div class="data-item__name">Кем выдан</div>
                            </div>
                            <div class="data-item">
                                <div class="data-item__value">{{$user->passport->issued_by}}</div>
                            </div>
                        </div>
                        <div class="row m-0">
                            <div class="data-item">
                                <div class="data-item__name">Код подразделения</div>
                            </div>
                            <div class="data-item">
                                <div class="data-item__value">{{$user->passport->division_code}}</div>
                            </div>
                        </div>
                        <div class="row m-0">
                            <div class="data-item">
                                <div class="data-item__name">Дата выдачи</div>
                            </div>
                            <div class="data-item">
                                <div
                                    class="data-item__value">{{date('d.m.Y', strtotime($user->passport->when_issued))}}</div>
                            </div>
                        </div>
                        <div class="row m-0">
                            <div class="data-item">
                                <div class="data-item__name">Адрес прописки</div>
                            </div>
                            <div class="data-item">
                                <div class="data-item__value"> @if ($address = $user->address_registration())
                                        {{$address->address}}
                                    @endif</div>
                            </div>
                        </div>
                        <div class="row m-0">
                            <div class="data-item">
                                <div class="data-item__name">Фактический адрес проживания</div>
                            </div>
                            <div class="data-item">
                                <div class="data-item__value"> @if ($address = $user->address_fact())
                                        {{$address->address}}
                                    @endif</div>
                            </div>
                        </div>
                        <div class="row m-0">
                            <div class="data-item">
                                <div class="data-item__name">ИНН</div>
                            </div>
                            <div class="data-item">
                                <div class="data-item__value"> @if ($inn = $user->inn)
                                        {{$inn->number}}
                                    @endif</div>
                            </div>
                        </div>
                        <div class="row m-0">
                            <div class="data-item">
                                <div class="data-item__name">СНИЛС</div>
                            </div>
                            <div class="data-item">
                                <div class="data-item__value"> @if ($snils = $user->snils)
                                        {{$snils->number}}
                                    @endif</div>
                            </div>
                        </div>
                    </div>
                    <div class="data-actions">
                        <button data-toggle="#editPhone" class="add popup-btn">
                            <span>Изменить телефон</span>
                        </button>
                        <button data-toggle="#editEmail" class="add popup-btn">
                            <span>Изменить email</span>
                        </button>
                    </div>
                </div>
            </div>
            <div id="tab-content2" class="user-data-list tab-content">
                <form action="#">
                    <div class="row m-0">
                        <h2 class="sub-heading">
                            Цели установления и предполагаемый характер деловых отношений клиента с организацией
                        </h2>
                    </div>
                    <div class="row m-0">
                        <div class="radio-block">
                            <div class="custom-radio">
                                <label class="custom-radio__label">
                                    <input type="radio" name="relations" id="">
                                    <div class="visible-wrapper">
                                        <div class="input-visible"></div>
                                    </div>
                                    <span>Присоединение к договору доверительного управления паевым инвестиционным фондом</span>
                                </label>
                            </div>
                        </div>
                        <div class="radio-block">
                            <div class="custom-radio">
                                <label class="custom-radio__label">
                                    <input type="radio" name="relations" id="">
                                    <div class="visible-wrapper">
                                        <div class="input-visible"></div>
                                    </div>
                                    <span>Иное</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row m-0">
                        <h2 class="sub-heading">
                            Сведения об источниках происхождения денежных средств
                        </h2>
                    </div>
                    <div class="row m-0">
                        <div class="checkbox-wrapper">
                            <label class="checkbox-label" data-dashlane-label="true">
                                <input class="checkbox-input" type="checkbox" name="" id=""
                                       data-dashlane-rid="691fd41d70bddfa1" data-form-type="other">
                                <div class="checkbox-visible"></div>
                                <span>
                                                <span>
                                                    Заработная плата
                                                </span>
                                            </span>
                            </label>
                        </div>
                        <div class="checkbox-wrapper">
                            <label class="checkbox-label" data-dashlane-label="true">
                                <input class="checkbox-input" type="checkbox" name="" id=""
                                       data-dashlane-rid="691fd41d70bddfa1" data-form-type="other">
                                <div class="checkbox-visible"></div>
                                <span>
                                                Доход от осуществления предпринимательской деятельности и (или) от участия в уставном (складочном) капитале коммерческой организации
                                            </span>
                            </label>
                        </div>
                        <div class="checkbox-wrapper">
                            <label class="checkbox-label" data-dashlane-label="true">
                                <input class="checkbox-input" type="checkbox" name="" id=""
                                       data-dashlane-rid="691fd41d70bddfa1" data-form-type="other">
                                <div class="checkbox-visible"></div>
                                <span>
                                                Получение наследства
                                            </span>
                            </label>
                        </div>
                        <div class="checkbox-wrapper">
                            <label class="checkbox-label" data-dashlane-label="true">
                                <input class="checkbox-input" type="checkbox" name="" id=""
                                       data-dashlane-rid="691fd41d70bddfa1" data-form-type="other">
                                <div class="checkbox-visible"></div>
                                <span>
                                                Получение активов по договору дарения
                                            </span>
                            </label>
                        </div>
                        <div class="checkbox-wrapper">
                            <label class="checkbox-label" data-dashlane-label="true">
                                <input class="checkbox-input" type="checkbox" name="" id=""
                                       data-dashlane-rid="691fd41d70bddfa1" data-form-type="other">
                                <div class="checkbox-visible"></div>
                                <span>
                                                Доход от операций с ценными бумагами и (или) иностранными финансовыми инструментами, неквалифицированными в соответствии с законодательством РФ в качестве ценных бумаг
                                            </span>
                            </label>
                        </div>
                        <div class="checkbox-wrapper">
                            <label class="checkbox-label" data-dashlane-label="true">
                                <input class="checkbox-input" type="checkbox" name="" id=""
                                       data-dashlane-rid="691fd41d70bddfa1" data-form-type="other">
                                <div class="checkbox-visible"></div>
                                <span>
                                                Доход от операций с производными финансовыми инструментами
                                            </span>
                            </label>
                        </div>
                        <div class="checkbox-wrapper">
                            <label class="checkbox-label" data-dashlane-label="true">
                                <input class="checkbox-input" type="checkbox" name="" id=""
                                       data-dashlane-rid="691fd41d70bddfa1" data-form-type="other">
                                <div class="checkbox-visible"></div>
                                <span>
                                                Доход от операций с иностранной валютой на организованных торгах и (или) на внебиржевом рынке (Forex)
                                            </span>
                            </label>
                        </div>
                        <div class="checkbox-wrapper">
                            <label class="checkbox-label" data-dashlane-label="true">
                                <input class="checkbox-input" type="checkbox" name="" id=""
                                       data-dashlane-rid="691fd41d70bddfa1" data-form-type="other">
                                <div class="checkbox-visible"></div>
                                <span>
                                                Иное
                                            </span>
                            </label>
                        </div>
                        <input type="text" class="input-default">
                    </div>
                    <div class="row m-0">
                        <h2 class="sub-heading">
                            Категория налогоплатильщика
                        </h2>
                    </div>
                    <div class="row m-0">
                        <div class="radio-block">
                            <div class="custom-radio">
                                <label class="custom-radio__label">
                                    <input type="radio" name="country" id="">
                                    <div class="visible-wrapper">
                                        <div class="input-visible"></div>
                                    </div>
                                    <span>Резидент Российской Федерации</span>
                                </label>
                            </div>
                        </div>
                        <div class="radio-block">
                            <div class="custom-radio">
                                <label class="custom-radio__label">
                                    <input type="radio" name="country" id="">
                                    <div class="visible-wrapper">
                                        <div class="input-visible"></div>
                                    </div>
                                    <span>Не резидент Российской Федерации</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row m-0">
                        <h2 class="sub-heading">
                            Субсидии и гранты
                        </h2>
                        <span class="gray">
                                        Являетесь ли Вы получателем субсидий, грантов или иных видов государственной поддержки за счет средств федерального бюджета, бюджета субъекта РФ или муниципального бюджета:
                                    </span>
                    </div>
                    <div class="row m-0">
                        <div class="radio-block">
                            <div class="custom-radio">
                                <label class="custom-radio__label">
                                    <input type="radio" name="yes" id="">
                                    <div class="visible-wrapper">
                                        <div class="input-visible"></div>
                                    </div>
                                    <span>Да</span>
                                </label>
                            </div>
                        </div>
                        <div class="radio-block">
                            <div class="custom-radio">
                                <label class="custom-radio__label">
                                    <input type="radio" name="yes" id="">
                                    <div class="visible-wrapper">
                                        <div class="input-visible"></div>
                                    </div>
                                    <span>Нет</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row m-0">
                        <h2 class="sub-heading">
                            Некоммерческие огранизации
                        </h2>
                        <span class="gray">
                                        Являетесь ли Вы учредителем или руководителем российской некоммерческой неправительственной организации, ее отделения, филиала или представительства?
                                    </span>
                    </div>
                    <div class="row m-0">
                        <div class="radio-block">
                            <div class="custom-radio">
                                <label class="custom-radio__label">
                                    <input type="radio" name="nko" id="">
                                    <div class="visible-wrapper">
                                        <div class="input-visible"></div>
                                    </div>
                                    <span>Нет</span>
                                </label>
                            </div>
                        </div>
                        <div class="radio-block">
                            <div class="custom-radio">
                                <label class="custom-radio__label">
                                    <input type="radio" name="nko" id="">
                                    <div class="visible-wrapper">
                                        <div class="input-visible"></div>
                                    </div>
                                    <span>Основатель</span>
                                </label>
                            </div>
                        </div>
                        <div class="radio-block">
                            <div class="custom-radio">
                                <label class="custom-radio__label">
                                    <input type="radio" name="nko" id="">
                                    <div class="visible-wrapper">
                                        <div class="input-visible"></div>
                                    </div>
                                    <span>Руководитель</span>
                                </label>
                            </div>
                        </div>
                        <span class="gray">
                                        Являетесь ли Вы учредителем или руководителем иностранной некоммерческой неправительственной организации, ее отделения, филиала или представительства?
                                    </span>
                        <div class="radio-block">
                            <div class="custom-radio">
                                <label class="custom-radio__label">
                                    <input type="radio" name="nko-role" id="">
                                    <div class="visible-wrapper">
                                        <div class="input-visible"></div>
                                    </div>
                                    <span>Нет</span>
                                </label>
                            </div>
                        </div>
                        <div class="radio-block">
                            <div class="custom-radio">
                                <label class="custom-radio__label">
                                    <input type="radio" name="nko-role" id="">
                                    <div class="visible-wrapper">
                                        <div class="input-visible"></div>
                                    </div>
                                    <span>Основатель</span>
                                </label>
                            </div>
                        </div>
                        <div class="radio-block">
                            <div class="custom-radio">
                                <label class="custom-radio__label">
                                    <input type="radio" name="nko-role" id="">
                                    <div class="visible-wrapper">
                                        <div class="input-visible"></div>
                                    </div>
                                    <span>Руководитель</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-block">
                            <span class="black">Название иностранной НКО:</span>
                            <input type="text" class="input-default">
                        </div>
                    </div>
                    <div class="row m-0">
                        <h2 class="sub-heading">
                            Публичность
                        </h2>
                        <span class="gray">
                                        Являетесь ли Вы российским официальным лицом?
                                    </span>
                    </div>
                    <div class="row m-0">
                        <div class="radio-block">
                            <div class="custom-radio">
                                <label class="custom-radio__label">
                                    <input type="radio" name="public" id="">
                                    <div class="visible-wrapper">
                                        <div class="input-visible"></div>
                                    </div>
                                    <span>Да</span>
                                </label>
                            </div>
                        </div>
                        <div class="radio-block">
                            <div class="custom-radio">
                                <label class="custom-radio__label">
                                    <input type="radio" name="public" id="">
                                    <div class="visible-wrapper">
                                        <div class="input-visible"></div>
                                    </div>
                                    <span>Нет</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-block">
                            <span class="black">Должность</span>
                            <input type="text" class="input-default">
                        </div>
                        <div class="form-block">
                            <span class="black">Организация</span>
                            <input type="text" class="input-default">
                        </div>
                        <div class="form-block">
                            <span class="black">Адрес организации</span>
                            <input type="text" class="input-default">
                        </div>
                    </div>
                    <div class="row m-0">
                        <span class="gray">Являетесь ли Вы иностранным официальным лицом?</span>
                        <div class="radio-block">
                            <div class="custom-radio">
                                <label class="custom-radio__label">
                                    <input type="radio" name="foreign-public" id="">
                                    <div class="visible-wrapper">
                                        <div class="input-visible"></div>
                                    </div>
                                    <span>Да</span>
                                </label>
                            </div>
                        </div>
                        <div class="radio-block">
                            <div class="custom-radio">
                                <label class="custom-radio__label">
                                    <input type="radio" name="foreign-public" id="">
                                    <div class="visible-wrapper">
                                        <div class="input-visible"></div>
                                    </div>
                                    <span>Нет</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row m-0">
                        <span class="gray">Являетесь ли Вы международным официальным лицом?</span>
                        <div class="radio-block">
                            <div class="custom-radio">
                                <label class="custom-radio__label">
                                    <input type="radio" name="international-public" id="">
                                    <div class="visible-wrapper">
                                        <div class="input-visible"></div>
                                    </div>
                                    <span>Да</span>
                                </label>
                            </div>
                        </div>
                        <div class="radio-block">
                            <div class="custom-radio">
                                <label class="custom-radio__label">
                                    <input type="radio" name="international-public" id="">
                                    <div class="visible-wrapper">
                                        <div class="input-visible"></div>
                                    </div>
                                    <span>Нет</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row m-0">
                        <h2 class="sub-heading">
                            Бенефициары
                        </h2>
                        <span class="gray">
                                        Cуществуют ли физические лица, имеющие возможность воздействовать на Ваши решения об осуществлении сделок, операций, на основании договора или иным образом?
                                    </span>
                    </div>
                    <div class="row m-0">
                        <div class="radio-block">
                            <div class="custom-radio">
                                <label class="custom-radio__label">
                                    <input type="radio" name="beneficiars" id="">
                                    <div class="visible-wrapper">
                                        <div class="input-visible"></div>
                                    </div>
                                    <span>Да</span>
                                </label>
                            </div>
                        </div>
                        <div class="radio-block">
                            <div class="custom-radio">
                                <label class="custom-radio__label">
                                    <input type="radio" name="beneficiars" id="">
                                    <div class="visible-wrapper">
                                        <div class="input-visible"></div>
                                    </div>
                                    <span>Нет</span>
                                </label>
                            </div>
                        </div>
                        <span class="gray">
                                        Действуете ли Вы в интересах (к выгоде) иностранного публичного должностного лица?
                                    </span>
                        <div class="radio-block">
                            <div class="custom-radio">
                                <label class="custom-radio__label">
                                    <input type="radio" name="beneficiars-interests" id="">
                                    <div class="visible-wrapper">
                                        <div class="input-visible"></div>
                                    </div>
                                    <span>Да</span>
                                </label>
                            </div>
                        </div>
                        <div class="radio-block">
                            <div class="custom-radio">
                                <label class="custom-radio__label">
                                    <input type="radio" name="beneficiars-interests" id="">
                                    <div class="visible-wrapper">
                                        <div class="input-visible"></div>
                                    </div>
                                    <span>Нет</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row m-0">
                        <h2 class="sub-heading">
                            Публичные должностные лица среди ближайших родственников
                        </h2>
                        <span class="gray">
                                        Являетесь ли Вы:
                                    </span>
                    </div>
                    <div class="row m-0">
                        <div class="checkbox-wrapper">
                            <label class="checkbox-label" data-dashlane-label="true">
                                <input class="checkbox-input" type="checkbox" name="" id=""
                                       data-dashlane-rid="691fd41d70bddfa1" data-form-type="other">
                                <div class="checkbox-visible"></div>
                                <span>
                                                <span>
                                                    Супругом/супругой российского публичного должностного лица
                                                </span>
                                            </span>
                            </label>
                        </div>
                        <div class="checkbox-wrapper">
                            <label class="checkbox-label" data-dashlane-label="true">
                                <input class="checkbox-input" type="checkbox" name="" id=""
                                       data-dashlane-rid="691fd41d70bddfa1" data-form-type="other">
                                <div class="checkbox-visible"></div>
                                <span>
                                                <span>
                                                    Родителем российского публичного должностного лица
                                                </span>
                                            </span>
                            </label>
                        </div>
                        <div class="checkbox-wrapper">
                            <label class="checkbox-label" data-dashlane-label="true">
                                <input class="checkbox-input" type="checkbox" name="" id=""
                                       data-dashlane-rid="691fd41d70bddfa1" data-form-type="other">
                                <div class="checkbox-visible"></div>
                                <span>
                                                <span>
                                                    Сыном/дочерью российского публичного должностного лица
                                                </span>
                                            </span>
                            </label>
                        </div>
                        <div class="checkbox-wrapper">
                            <label class="checkbox-label" data-dashlane-label="true">
                                <input class="checkbox-input" type="checkbox" name="" id=""
                                       data-dashlane-rid="691fd41d70bddfa1" data-form-type="other">
                                <div class="checkbox-visible"></div>
                                <span>
                                                <span>
                                                    Дедушкой/бабушкой российского публичного должностного лица
                                                </span>
                                            </span>
                            </label>
                        </div>
                        <div class="checkbox-wrapper">
                            <label class="checkbox-label" data-dashlane-label="true">
                                <input class="checkbox-input" type="checkbox" name="" id=""
                                       data-dashlane-rid="691fd41d70bddfa1" data-form-type="other">
                                <div class="checkbox-visible"></div>
                                <span>
                                                <span>
                                                    Внуком/внучкой российского публичного должностного лица
                                                </span>
                                            </span>
                            </label>
                        </div>
                        <div class="checkbox-wrapper">
                            <label class="checkbox-label" data-dashlane-label="true">
                                <input class="checkbox-input" type="checkbox" name="" id=""
                                       data-dashlane-rid="691fd41d70bddfa1" data-form-type="other">
                                <div class="checkbox-visible"></div>
                                <span>
                                                <span>
                                                    Усыновителем российского публичного должностного лица
                                                </span>
                                            </span>
                            </label>
                        </div>
                        <div class="checkbox-wrapper">
                            <label class="checkbox-label" data-dashlane-label="true">
                                <input class="checkbox-input" type="checkbox" name="" id=""
                                       data-dashlane-rid="691fd41d70bddfa1" data-form-type="other">
                                <div class="checkbox-visible"></div>
                                <span>
                                                <span>
                                                    Усыновлённым российским публичним должностным лицом
                                                </span>
                                            </span>
                            </label>
                        </div>
                        <div class="checkbox-wrapper">
                            <label class="checkbox-label" data-dashlane-label="true">
                                <input class="checkbox-input" type="checkbox" name="" id=""
                                       data-dashlane-rid="691fd41d70bddfa1" data-form-type="other">
                                <div class="checkbox-visible"></div>
                                <span>
                                                <span>
                                                    Супругом/супругой иностранного публичного должностного лица
                                                </span>
                                            </span>
                            </label>
                        </div>
                        <div class="checkbox-wrapper">
                            <label class="checkbox-label" data-dashlane-label="true">
                                <input class="checkbox-input" type="checkbox" name="" id=""
                                       data-dashlane-rid="691fd41d70bddfa1" data-form-type="other">
                                <div class="checkbox-visible"></div>
                                <span>
                                                <span>
                                                    Родителем иностранного публичного должностного лица
                                                </span>
                                            </span>
                            </label>
                        </div>
                        <div class="checkbox-wrapper">
                            <label class="checkbox-label" data-dashlane-label="true">
                                <input class="checkbox-input" type="checkbox" name="" id=""
                                       data-dashlane-rid="691fd41d70bddfa1" data-form-type="other">
                                <div class="checkbox-visible"></div>
                                <span>
                                                <span>
                                                    Сыном/дочерью иностранного публичного должностного лица
                                                </span>
                                            </span>
                            </label>
                        </div>
                        <div class="checkbox-wrapper">
                            <label class="checkbox-label" data-dashlane-label="true">
                                <input class="checkbox-input" type="checkbox" name="" id=""
                                       data-dashlane-rid="691fd41d70bddfa1" data-form-type="other">
                                <div class="checkbox-visible"></div>
                                <span>
                                                <span>
                                                    Дедушкой/бабушкой иностранного публичного должностного лица
                                                </span>
                                            </span>
                            </label>
                        </div>
                        <div class="checkbox-wrapper">
                            <label class="checkbox-label" data-dashlane-label="true">
                                <input class="checkbox-input" type="checkbox" name="" id=""
                                       data-dashlane-rid="691fd41d70bddfa1" data-form-type="other">
                                <div class="checkbox-visible"></div>
                                <span>
                                                <span>
                                                    Внуком/внучкой иностранного публичного должностного лица
                                                </span>
                                            </span>
                            </label>
                        </div>
                        <div class="checkbox-wrapper">
                            <label class="checkbox-label" data-dashlane-label="true">
                                <input class="checkbox-input" type="checkbox" name="" id=""
                                       data-dashlane-rid="691fd41d70bddfa1" data-form-type="other">
                                <div class="checkbox-visible"></div>
                                <span>
                                                <span>
                                                    Усыновителем иностранного публичного должностного лица
                                                </span>
                                            </span>
                            </label>
                        </div>
                        <div class="checkbox-wrapper">
                            <label class="checkbox-label" data-dashlane-label="true">
                                <input class="checkbox-input" type="checkbox" name="" id=""
                                       data-dashlane-rid="691fd41d70bddfa1" data-form-type="other">
                                <div class="checkbox-visible"></div>
                                <span>
                                                <span>
                                                    Усыновлённым иностранным публичним должностным лицом
                                                </span>
                                            </span>
                            </label>
                        </div>
                        <div class="checkbox-wrapper">
                            <label class="checkbox-label" data-dashlane-label="true">
                                <input class="checkbox-input" type="checkbox" name="" id=""
                                       data-dashlane-rid="691fd41d70bddfa1" data-form-type="other">
                                <div class="checkbox-visible"></div>
                                <span>
                                                <span>
                                                    Супругом/супругой международного публичного должностного лица

                                                </span>
                                            </span>
                            </label>
                        </div>
                        <div class="checkbox-wrapper">
                            <label class="checkbox-label" data-dashlane-label="true">
                                <input class="checkbox-input" type="checkbox" name="" id=""
                                       data-dashlane-rid="691fd41d70bddfa1" data-form-type="other">
                                <div class="checkbox-visible"></div>
                                <span>
                                                <span>
                                                    Родителем международного публичного должностного лица
                                                </span>
                                            </span>
                            </label>
                        </div>
                        <div class="checkbox-wrapper">
                            <label class="checkbox-label" data-dashlane-label="true">
                                <input class="checkbox-input" type="checkbox" name="" id=""
                                       data-dashlane-rid="691fd41d70bddfa1" data-form-type="other">
                                <div class="checkbox-visible"></div>
                                <span>
                                                <span>
                                                    Сыном/дочерью международного публичного должностного лица
                                                </span>
                                            </span>
                            </label>
                        </div>
                        <div class="checkbox-wrapper">
                            <label class="checkbox-label" data-dashlane-label="true">
                                <input class="checkbox-input" type="checkbox" name="" id=""
                                       data-dashlane-rid="691fd41d70bddfa1" data-form-type="other">
                                <div class="checkbox-visible"></div>
                                <span>
                                                <span>
                                                    Дедушкой/бабушкой международного публичного должностного лица
                                                </span>
                                            </span>
                            </label>
                        </div>
                        <div class="checkbox-wrapper">
                            <label class="checkbox-label" data-dashlane-label="true">
                                <input class="checkbox-input" type="checkbox" name="" id=""
                                       data-dashlane-rid="691fd41d70bddfa1" data-form-type="other">
                                <div class="checkbox-visible"></div>
                                <span>
                                                <span>
                                                    Внуком/внучкой международного публичного должностного лица
                                                </span>
                                            </span>
                            </label>
                        </div>
                        <div class="checkbox-wrapper">
                            <label class="checkbox-label" data-dashlane-label="true">
                                <input class="checkbox-input" type="checkbox" name="" id=""
                                       data-dashlane-rid="691fd41d70bddfa1" data-form-type="other">
                                <div class="checkbox-visible"></div>
                                <span>
                                                <span>
                                                    Усыновителем международного публичного должностного лица
                                                </span>
                                            </span>
                            </label>
                        </div>
                        <div class="checkbox-wrapper">
                            <label class="checkbox-label" data-dashlane-label="true">
                                <input class="checkbox-input" type="checkbox" name="" id=""
                                       data-dashlane-rid="691fd41d70bddfa1" data-form-type="other">
                                <div class="checkbox-visible"></div>
                                <span>
                                                <span>
                                                    Усыновлённым международным публичним должностным лицом
                                                </span>
                                            </span>
                            </label>
                        </div>
                    </div>
                    <div class="row m-0">
                        <h2 class="sub-heading">
                            Предпринимательская деятельность
                        </h2>
                        <span class="gray">
                                        Являетесь ли Вы предпринимателем?
                                    </span>
                    </div>
                    <div class="row m-0">
                        <div class="radio-block">
                            <div class="custom-radio">
                                <label class="custom-radio__label">
                                    <input type="radio" name="bussinesMan" id="">
                                    <div class="visible-wrapper">
                                        <div class="input-visible"></div>
                                    </div>
                                    <span>Да</span>
                                </label>
                            </div>
                        </div>
                        <div class="radio-block">
                            <div class="custom-radio">
                                <label class="custom-radio__label">
                                    <input type="radio" name="bussinesMan" id="">
                                    <div class="visible-wrapper">
                                        <div class="input-visible"></div>
                                    </div>
                                    <span>Нет</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row m-0">
                        <div class="form-block">
                            <span class="black">Наименование регистрирующего органа:</span>
                            <input type="text" class="input-default" value="Налоговая инспекция">
                        </div>
                        <div class="form-block">
                            <span class="black">Адрес регистрирующего органа:</span>
                            <input type="text" class="input-default" value="Адрес регистрирующего органа:">
                        </div>
                        <div class="form-block">
                            <span class="black">Дата регистрации:</span>
                            <input type="text" class="input-default">
                        </div>
                        <div class="form-block">
                            <span class="black">Государственный регистрационный номер (ОГРНИП):</span>
                            <input type="text" class="input-default">
                        </div>
                        <div class="form-block">
                            <span class="black">Виды деятельности, осуществляемые предпринимателем:</span>
                            <textarea class="input-default" name="" id="" cols="30" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="row m-0">
                                    <span class="gray">
                                        Имеются ли лицензии:
                                    </span>
                    </div>
                    <div class="row m-0">
                        <div class="radio-block">
                            <div class="custom-radio">
                                <label class="custom-radio__label">
                                    <input type="radio" name="license" id="">
                                    <div class="visible-wrapper">
                                        <div class="input-visible"></div>
                                    </div>
                                    <span>Да</span>
                                </label>
                            </div>
                        </div>
                        <div class="radio-block">
                            <div class="custom-radio">
                                <label class="custom-radio__label">
                                    <input type="radio" name="license" id="">
                                    <div class="visible-wrapper">
                                        <div class="input-visible"></div>
                                    </div>
                                    <span>Нет</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row m-0">
                                    <span class="gray">
                                        Финансовое положение
                                    </span>
                    </div>
                    <div class="row m-0">
                        <div class="radio-block">
                            <div class="custom-radio">
                                <label class="custom-radio__label">
                                    <input type="radio" name="financial" id="">
                                    <div class="visible-wrapper">
                                        <div class="input-visible"></div>
                                    </div>
                                    <span>Положительное</span>
                                </label>
                            </div>
                        </div>
                        <div class="radio-block">
                            <div class="custom-radio">
                                <label class="custom-radio__label">
                                    <input type="radio" name="financial" id="">
                                    <div class="visible-wrapper">
                                        <div class="input-visible"></div>
                                    </div>
                                    <span>Отрицательное</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row m-0">
                        <div class="form-block">
                            <span class="black">Сведения о деловой репутации (не обязательно):</span>
                            <textarea class="input-default" name="" id="" cols="30" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="row m-0">
                        <span class="black">Файл со сведениями о деловой репутации (не обязательно):</span>
                    </div>
                    <div class="row m-0">
                        <div class="form-block">
                            <input type="file">
                        </div>
                    </div>
                    <div class="row m-0">
                        <span class="black">Ранее загруженный файл</span>
                    </div>
                    <div class="row m-0">
                        <div class="form-block">
                            <a class="green" href="#">0606.pdf</a>
                        </div>
                    </div>
                    <div class="row m-0">
                        <div class="form-block">
                            <span class="black">Реквизиты для перечисления бумаг</span>
                            <textarea class="input-default" name="" id="" cols="30" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="row m-0 center">
                        <button class="green-btn" data-dashlane-rid="7f6e059ca3c826e4" data-dashlane-label="true"
                                data-form-type="action">Отправить
                        </button>
                    </div>
                    <div class="row m-0">
                        <div class="row m-0">
                            <div class="mr-2">Дата начала заполения:</div>
                            <div>06.07.2020 18:12</div>
                        </div>
                        <div class="row m-0">
                            <div class="mr-2">Последнее обновление:</div>
                            <div>29.09.2020 05:01</div>
                        </div>
                        <div class="row m-0">
                            <div class="mr-2">Заполнение анкеты:</div>
                            <div>14.07.2020 10:16</div>
                        </div>
                    </div>
                </form>
            </div>
            @include('snippets.bank_accounts')
        </div>
    </div>
@stop
