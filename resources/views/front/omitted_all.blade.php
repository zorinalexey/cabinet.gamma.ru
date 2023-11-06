@extends('layouts.cabinet')
@section('title', 'Инвестиционный комитет');
@section('content')
    <div class="row">
        <div class="breadcrumbs">
            <a href="{{route('cabinet')}}" class="breadcrumbs-item">Личный кабинет</a>
            <div class="separator">|</div>
            <a href="{{route('omitted')}}" class="breadcrumbs-item">Инвестиционный комитет</a>
        </div>
    </div>
    <div class="content-body">
        <div class="fundList">
            @if(count($omitteds)>0)
                <article class="fundList__item table-title">
                    <h4>Название фонда</h4>
                    <p>Решение о проведении ИК</p>
                    <h4>Дата проведения</h4>
                    <p>Период голосования</p>
                    <h4>Бюллетень для голосования</h4>
                    <p>Статус</p>
                </article>
                @foreach ($omitteds AS $item)
                    <article class="fundList__item">
                        <a href="{{route('view_omitted', $item->id)}}">{{$item->fund->name}}</a>
                        <p>{{date('d.m.Y', strtotime($item->start_date))}}
                            — {{date('d.m.Y', strtotime($item->end_date))}}</p>
                        @if($item->documents)
                        <a href="{{route('user.omitted.upload.file', [$item->documents->id])}}" download>{{$item->documents->name}}</a>
                        @endif
                    </article>
                @endforeach
            @else
                Список голосований пуст
            @endif
        </div>
    </div>
@stop
