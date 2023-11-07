@php use Illuminate\Support\Facades\Auth; @endphp
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
                    <h4>Фонд</h4>
                    <h4>Решение о проведении ИК</h4>
                    <h4>Дата предоставления решения</h4>
                    <h4>Период голосования</h4>
                    <h4>Бюллетень для голосования</h4>
                    <h4>Статус</h4>
                </article>
                @php
                    $user = Auth::user();
                @endphp
                @foreach ($omitteds AS $item)
                    @php
                        $status = $item->status();
                        $bulluten = $item->getUserBulletin($user->id);
                    @endphp
                    <article class="fundList__item table-title">
                        <h4><a href="{{route('fund', $item->fund->id)}}">{{$item->fund->name}}</a></h4>
                        <h4>
                            @if($item->documents)
                                <a href="{{route('user.omitted.upload.file', [$item->documents->id])}}"
                                   download>{{$item->documents->name}}</a>
                            @endif
                        </h4>
                        <h4>
                            {{date('d.m.Y', strtotime($item->total_date))}}
                        </h4>
                        <h4>
                            {{date('d.m.Y', strtotime($item->start_date))}}
                            — {{date('d.m.Y', strtotime($item->end_date))}}
                        </h4>
                        <h4>
                            @if($status === 'Открыт' && !$bulluten)
                                <a href="{{route('voting', $item->id)}}">Проголосовать</a>
                            @elseif($status === 'Открыт' && $bulluten && !$bulluten->sign_status)
                                <a href="{{$bulluten->path}}" target="_blank">Посмотреть бюллетень</a>
                                <br/>
                                <a href="{{route('sign_document', $bulluten->id)}}">Подписать бюллетень</a>
                                <br/>
                                <a href="{{route('voting', $item->id)}}">Проголосовать заново</a>
                            @elseif($bulluten?->sign_status)
                                Ваш голос учтен <br/>
                                <a href="{{$bulluten->path}}" target="_blank">Бюллетень голосования</a>
                            @else
                                Ваш голос не учтен
                            @endif
                        </h4>
                        <h4>
                            @if($status === 'Открыт')
                                ИК проводится до {{date('d.m.Y H:i', strtotime($item->end_date))}} по Новосибирскому времени
                            @else
                                Голосование завершено
                            @endif
                        </h4>
                    </article>
                @endforeach
            @else
                Список голосований пуст
            @endif
        </div>
    </div>
@stop
