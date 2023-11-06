@extends('layouts.admin')
@section('title', 'Создать новый пост')

@section('bredcrumb')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">@yield('title')</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin_main')}}">Админ-панель</a></li>
                    <li class="breadcrumb-item"><a href="{{route('admin_index', ['news'])}}">Новости</a></li>
                    <li class="breadcrumb-item active">Новый пост</li>
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
                    <form action="{{route('admin_store', ['news'])}}" method="POST">
                        @csrf
                        <div class="form-body">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Заголовок</label>
                                    <input class="form-control" name="title"
                                           placeholder="Введите заголовок нового поста">
                                    <small class="form-control-feedback">Введите заголовок нового поста</small>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Содержание поста</label>
                                        <textarea id="editor" class="form-control" name="content"
                                                  placeholder="Введите содержание поста"></textarea>
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
