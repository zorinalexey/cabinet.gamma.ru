@extends('layouts.admin')
@section('title', 'Редактировать пост "'.$post->title.'"')

@section('bredcrumb')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">@yield('title')</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.main')}}">Админ-панель</a></li>
                    <li class="breadcrumb-item"><a href="{{route('admin.post.create')}}">Новости</a></li>
                    <li class="breadcrumb-item active">Редактировать пост</li>
                </ol>
            </div>
        </div>
    </div>
@endsection
@php
    $path = 'news';
@endphp

@section('scripts')
    @include('snippets.admin.editor')
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('admin_update', ['news', $post->id])}}" method="POST">
                        @csrf
                        <div class="form-body">
                            <input type="hidden" name="id" value="{{$post->id}}">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Заголовок</label>
                                    <input class="form-control" name="title" value="{{$post->title}}"
                                           placeholder="Введите заголовок нового поста">
                                    <small class="form-control-feedback">Введите заголовок поста</small>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Содержание поста</label>
                                        <textarea id="editor" class="form-control" name="content"
                                                  placeholder="Введите содержание поста">{!! $post->content !!}</textarea>
                                        <small class="form-control-feedback">Введите содержание поста</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
