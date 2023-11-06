@extends('layouts.cabinet')
@section('title', 'Личный кабинет')

@section('content')
    <div class="content-body">
        <div class="row">
            <h1 class="main-greeting">
                Добрый день, {{Auth::user()->name}}!
            </h1>
            <div class="row-panels">
                <div class="row-item">
                    <div class="row-line">
                        <span class="money-count">{{Auth::user()->all_cost_pif()}}₽</span>
                        <span class="icon">
                                                <div class="shadow"></div>
                                                <div class="circle"></div>
                                                <div id="notification-4" class="notification">
                                                    <div class="notification-row">
                                                        Найти любую необходимую информацию о Фондах вы можете через Поиск
                                                    </div>
                                                    <button class="next-step">
                                                        <span>Далее</span>
                                                        <svg width="44" height="8" viewBox="0 0 44 8" fill="none"
                                                             xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M43.3535 4.35355C43.5488 4.15829 43.5488 3.84171 43.3535 3.64645L40.1716 0.464466C39.9763 0.269204 39.6597 0.269204 39.4645 0.464466C39.2692 0.659728 39.2692 0.976311 39.4645 1.17157L42.2929 4L39.4645 6.82843C39.2692 7.02369 39.2692 7.34027 39.4645 7.53553C39.6597 7.7308 39.9763 7.7308 40.1716 7.53553L43.3535 4.35355ZM0 4.5H43V3.5H0V4.5Z"
                                                            fill="#A7CF43"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                                <div class="open-actions">
                                                    <svg class="svg-icon" width="3" height="13" viewBox="0 0 3 13"
                                                         fill="none"
                                                         xmlns="http://www.w3.org/2000/svg">
                                                    <circle cx="1.5" cy="1.5" r="1.5" fill="#B8B8B8"/>
                                                    <circle cx="1.5" cy="6.5" r="1.5" fill="#B8B8B8"/>
                                                    <circle cx="1.5" cy="11.5" r="1.5" fill="#B8B8B8"/>
                                                    </svg>
                                                </div>
                                            </span>
                    </div>
                    <div class="row-line actions-line">
                        <div class="row-item-actions">
                            <ul class="actions-list">
                                <li class="actionst-list-item">
                                    <a href="/return/money" class="actions-list-link">
                                        Вывести деньги
                                    </a>
                                </li>
                                <li class="actionst-list-item">
                                    <a href="/pay/pif" class="actions-list-link">
                                        Купить ПАИ
                                    </a>
                                </li>
                                <li class="actionst-list-item">
                                    <a href="/view/accounting" class="actions-list-link">
                                        Посмотреть отчетность
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row-line">
                                            <span class="dscr-span">
                                                Общая оценочная стоимость
                                                ваших ПАИ
                                            </span>
                    </div>
                </div>
                <div class="row-item">
                    <div class="row-line">
                                            <span class="row-line__span">
                                                Мы собрали для вас
                                                доступные к покупке ПАИ
                                            </span>
                    </div>
                    <div class="row-line">
                        <a class="btn-green" href="/funds">Посмотреть</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <h2 class="row-heading">Ваши фонды</h2>
            <div class="green-panels">
                @foreach(Auth::user()->funds as $fund)
                    <div class="row-item green">
                        <div class="row-line">
                            <div class="row-item__heading">
                                <a href="/fund/{{$fund->fund->id}}">{{$fund->fund->name}}</a>
                            </div>
                        </div>
                        <div class="row-line">
                                            <span class="item-price">
                                                {{$fund->scha()}} ₽ СЧА
                                            </span>
                            @if($fund->fund->growth_calculation_percent() >= 0)
                                <!--Иконка если цена растет-->
                                <svg class="st-icon" width="14" height="11" viewBox="0 0 14 11" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7 0L13.0622 10.5H0.937822L7 0Z" fill="#A7CF43"/>
                                </svg>
                            @else
                                <!--Иконка если падает-->
                                <svg class="st-icon" width="14" height="11" viewBox="0 0 14 11" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7 11L13.0622 0.5H0.937822L7 11Z" fill="#CC0000"/>
                                </svg>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@stop
