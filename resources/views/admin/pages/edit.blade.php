@extends('layouts.admin')
@section('title', 'Редактировать страницу "'.$page->title.'"')

@section('bredcrumb')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">@yield('title')</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.main')}}">Админ-панель</a></li>
                    <li class="breadcrumb-item"><a href="{{route('admin.post.create')}}">Страницы</a></li>
                    <li class="breadcrumb-item active">Редактировать страницу</li>
                </ol>
            </div>
        </div>
    </div>
@endsection
@php
    $path = 'pages';
@endphp

@section('scripts')
    @include('snippets.admin.editor')
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('admin.page.update', $page->id)}}" method="POST">
                        @csrf
                        <div class="form-body">
                            <input type="hidden" name="id" value="{{$page->id}}">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Заголовок</label>
                                    <input class="form-control" name="title" value="{{$page->title}}"
                                           placeholder="Введите заголовок страницы">
                                    <small class="form-control-feedback">Введите заголовок страницы</small>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Содержание страницы</label>
                                        <textarea id="editor" class="form-control" name="content"
                                                  placeholder="Введите содержание страницы">{!! $page->content !!}</textarea>
                                        <small class="form-control-feedback">Введите содержание страницы</small>
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
