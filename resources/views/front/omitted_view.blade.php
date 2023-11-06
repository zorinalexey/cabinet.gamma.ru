@extends('layouts.cabinet')
@section('title', $omitted->name);
@section('content')
    <div class="content-body overflow-hidden">
        <div class="row">
            <div class="breadcrumbs">
                <a href="{{route('cabinet')}}" class="breadcrumbs-item">Личный кабинет</a>
                <div class="separator">|</div>
                <a href="{{route('omitted')}}" class="breadcrumbs-item">Инвестиционный комитет</a>
                <div class="separator">|</div>
                <a href="{{route('view_omitted', $omitted->id)}}" class="breadcrumbs-item">{{$omitted->name}}</a>
            </div>
        </div>
        <div class="content-body">
            <div class="currentFund">
                <h1 class="currentFund__title">{{$omitted->fund->name}}</h1>
                <p class="currentFund__date">{{date('d.m.Y', strtotime($omitted->start_date))}}
                    — {{date('d.m.Y', strtotime($omitted->end_date))}}</p>
                <p class="currentFund__descr">
                    Управляющая компания предоставляет Решение о проведении заседания Инвестиционного комитета, просим
                    принять решения об одобрении сделок, выносимых на Инвестиционный комитет путём заполнения бюллетеня.
                </p>
                @if($omitted->documents)
                <a class="currentFund__link" href="{{route('user.omitted.upload.file', [$omitted->documents->id])}}" download>{{$omitted->documents->name}}</a>
                @endif
                <a href="{{route('voting', $omitted->id)}}" class="currentFund__btn green-btn">Заполнить бюллетень</a>
            </div>
        </div>

    </div>
@stop
