@extends('layouts.admin')

@section('title', 'Технический раздел')

@section('bredcrumb')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">@yield('title')</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.main')}}">Админ-панель</a></li>
                    <li class="breadcrumb-item active">@yield('title')</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('admin.tech.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-body">
                            <h3 class="card-title">Загрузка информационных баз</h3>
                            <hr>
                            <div class="row p-t-20">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">МВК</label>
                                        <input type="file" class="form-control" name="mvk">
                                        <small class="form-control-feedback">Выберите файл базы МВК</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">ФРОМУ</label>
                                        <input type="file" class="form-control" name="fromu">
                                        <small class="form-control-feedback">Выберите файл базы ФРОМУ</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">ПОД/ФТ</label>
                                        <input type="file" class="form-control" name="podft">
                                        <small class="form-control-feedback">Выберите файл базы ПОД/ФТ</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">П-639</label>
                                        <input type="file" class="form-control" name="p639">
                                        <small class="form-control-feedback">Выберите файл базы П-639</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Загрузить</button>
                            <button type="reset" class="btn btn-info"> Очистить все</button>
                            <a href="{{route('admin.main')}}" type="button" class="btn btn-inverse">На главную</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
