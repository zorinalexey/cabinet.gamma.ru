@php use App\Http\Services\OmittedService;use Illuminate\Support\Facades\Auth; @endphp
@extends('layouts.cabinet')
@section('title', '');
@section('content')
    <div class="content-body content-body-voting">
        <div class="voting">
            <div class="voting__wrapper">
                <h1 class="voting__title h1">Бюллетень для голосования на инвестиционном комитете владельцев
                    инвестиционных паев</h1>
                <p class="voting__fund-name voting__fund-info">
                    <span>Название фонда:</span>
                    <span>«{{$omitted->fund->name}}»</span>
                </p>
                <p class="voting__fund-fullname voting__fund-info">
                    <span>Полное фирменное наименование управляющей компании фонда:</span>
                    <span>Общество с ограниченной ответственностью Управляющая компания «Гамма Групп».</span>
                </p>
                <p class="voting__fund-voting voting__fund-info">
                    <span>Форма проведения заседания инвестиционного комитета:</span>
                    <span>Заочное голосование</span>
                </p>
                <p class="voting__fund-result date-result voting__fund-info">
                    <span>Дата подведения итогов заседания инвестиционного комитета:</span>
                    <span>{{date('d.m.Y', strtotime($omitted->total_date))}}</span>
                </p>
                <p class="voting__fund-reception voting__fund-info">
                    <span>Дата окончания приема заполненных бюллетеней для голосования:</span>
                    <span>
                        до {{date('H.i', strtotime($omitted->end_date))}}
                        по новосибирскому времени {{date('d.m.Y', strtotime($omitted->end_date))}} г.
                    </span>
                </p>
                <p class="voting__fund-address voting__fund-info">
                    <span>Почтовый адрес, по которому должны направляться заполненные бюллетени для голосования:</span>
                    <span>630049, Новосибирская область, Г.О. Город Новосибирск, г. Новосибирск, Пр-кт Красный, д. 157/1, офис 215</span>
                </p>
            </div>
            <form method="POST" id="votingForm" action="{{route('voting_save', $omitted->id)}}">
                @csrf
                <input type="hidden" name="omitted" value="{{$omitted->id}}">
                <div class="voting__wrapper voting__wrapper--sec">
                    <h2 class="voting__title h2">Вопросы, поставленные на голосование</h2>
                    <p class="voting__subtitle">Оставьте только один вариант голосования по вопросу.</p>
                    @foreach($omitted->votings as $item)
                        @php
                            $check_answer = OmittedService::checkAnswer(Auth::user()->id, $omitted->id, $item->id);
                        @endphp
                        <div class="voting__list">
                            <div class="voting__list-item">
                                <div class="voting__list-item-info">
                                    <p>Вид сделки:</p>
                                    <p>Стороны по сделке</p>
                                    <p>Предмет сделки:</p>
                                    <p>Цена сделки:</p>
                                    @if($item->other_conditions)
                                    <p>Дополнительные условия:</p>
                                    @endif
                                </div>
                                <div class="voting__list-item-info">
                                    <p>{{$item->type_transaction}}</p>
                                    <p>{{$item->parties_transaction}}</p>
                                    <p>{{$item->subject_transaction}}</p>
                                    <p>{{$item->cost_transaction}} р</p>
                                    @if($item->other_conditions)
                                    <p>{{$item->other_conditions}}</p>
                                    @endif
                                </div>
                                <div class="voting__list-item-actions">
                                    <label class="voting-radio voting-radio-yes">
                                        <input @if($vote || $omitted->status() === 'Закрыт') disabled @else checked
                                               @endif @if($check_answer)
                                               @endif type="radio" value="За" name="answer[{{$item->id}}]">
                                        <span>За</span>
                                    </label>
                                    <label class="voting-radio voting-radio-no">
                                        <input @if($vote || $omitted->status() === 'Закрыт') disabled
                                               @endif type="radio" @if(!$check_answer)
                                               @endif value="Против" name="answer[{{$item->id}}]">
                                        <span>Против</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </form>
        </div>
        <div class="voting__wrapper voting__wrapper--third">
            <h1 class="voting__title h2">Данные, необходимые для идентификации лица, включенного в список лиц, имеющих
                право на участие в заседании Инвестиционного комитета:</h1>
            <p class="voting__fund-name voting__fund-info">
                <span>ФИО</span>
                <span>{{$user->lastname}} {{$user->name}} {{$user->patronymic}}</span>
            </p>
            <p class="voting__fund-fullname voting__fund-info">
                <span>Паспорт гражданина РФ</span>
                <span>{{$user->passport->series}} {{$user->passport->number}}</span>
            </p>
            <p class="voting__fund-voting voting__fund-info">
                <span>Количество инвестиционных паев, принадлежащих лицу, принимающим участие в заседании Инвестиционного комитета:</span>
                <span>{{$user->countPifUserByFund($omitted->fund)}}</span>
            </p>
            <div class="voting__fund-actions">
                @if(!$vote && $omitted->status() !== 'Закрыт')
                    <button form="votingForm" class="green-btn">Проголосовать</button>
                @endif
                <a class="voting__fund-link" href="{{$paper_ballot_link}}" target="_blank">Просмотр бумажной формы</a>
            </div>
        </div>
    </div>
@stop

