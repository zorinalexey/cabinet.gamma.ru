@extends('layouts.front_main')

@section('title','Новости')

@section('header_scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: #fff;">
            <li class="breadcrumb-item"><a href="/">Личный кабинет</a></li>
            <li class="breadcrumb-item active" aria-current="page">Новости</li>
        </ol>
    </nav>
    <div class="content-body content-documents">
        <ul class="news">
            @foreach($news as $item)
                <li class="news-item">
                    @php
                        if($route === 'news'){
                            $url = route($route).'/'.$item->alias;
                        }else{
                            $url = route($route, $item->alias);
                        }
                    @endphp
                    <a class="news-item__link" href="{{$url}}">
                        {{$item->title}}
                    </a>
                    <p class="news-item__content d-none">
                        {{$item->short_content()}}
                    </p>
                </li>
            @endforeach
        </ul>
    </div>
@stop
