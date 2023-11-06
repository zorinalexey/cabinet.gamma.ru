@extends('layouts.front_main')

@section('title', $post->title)


@section('header_scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: #fff;">
            <li class="breadcrumb-item"><a href="/">Личный кабинет</a></li>
            <li class="breadcrumb-item"><a href="{{route('news')}}">Новости</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{$title}}</li>
        </ol>
    </nav>
    <div class="content-body content-documents">

        <div class="news-heading">
            <h4 class="news-item__head" href="{{route('post', $post->alias)}}">
                {{$title}}
            </h4>
            <div class="news-item__actions">
                <div class="actions-date">{{date('d.m.Y', strtotime($post->created_at))}}</div>
            </div>
        </div>
        {!! $post->content !!}
        @if($post->path)
            <a href="{{$post->path}}" class="news-item__link news-full_link">скачать документ</a>
        @endif
    </div>
@stop
