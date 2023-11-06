@php use Illuminate\Support\Facades\Storage; @endphp
@extends('layouts.admin')

@section('title', 'Список файлов спец.репозитория Инфинитум')

@section('bredcrumb')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">@yield('title')</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin_main')}}">Админ-панель</a></li>
                    <li class="breadcrumb-item active">@yield('title')</li>
                </ol>
            </div>
        </div>
    </div>
@endsection
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body p-b-0">
                    <h4 class="card-title">@yield('title')</h4>
                </div>
                <ul class="nav nav-tabs customtab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#home2" role="tab">
                            <span class="hidden-xs-down">Новые файлы</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#profile2" role="tab">
                            <span class="hidden-xs-down">Скачанные файлы</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#profile3" role="tab">
                            <span class="hidden-xs-down">Корзина</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="home2" role="tabpanel">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Наименование</th>
                                <th>Дата создания</th>
                                <th>Действия</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($new_files as $file)
                                <tr>
                                    <td>
                                        <a href="{{route('admin_upload', ['infinitum', $file->id])}}" target="_blank">{{$file->name}}</a>
                                        <div>
                                            Инвестор:
                                            <a href="{{route('admin_show', ['users', $file->user->id])}}" target="_blank">
                                            {{$file->user->lastname.' '.$file->user->name.' '.$file->user->patronymic.' '}}
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        {{date('d.m.Y H:i:s', strtotime($file->created_at))}}
                                    </td>
                                    <td class="text-nowrap">
                                        <a href="{{route('admin_destroy', ['infinitum', $file->id])}}" data-toggle="tooltip" data-original-title="Удалить в корзину">
                                            <i class="mdi mdi-delete-forever"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $new_files->links() }}
                    </div>
                    <div class="tab-pane" id="profile2" role="tabpanel">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Наименование</th>
                                <th>Дата создания</th>
                                <th>Количество скачиваний</th>
                                <th>Действия</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($download_files as $file)
                                <tr>
                                    <td>
                                        <a href="{{route('admin_upload', ['infinitum', $file->id])}}" target="_blank">{{$file->name}}</a>
                                        <div>
                                            Инвестор:
                                            <a href="{{route('admin_show', ['users', $file->user->id])}}" target="_blank">
                                                {{$file->user->lastname.' '.$file->user->name.' '.$file->user->patronymic.' '}}
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        {{date('d.m.Y H:i:s', strtotime($file->created_at))}}
                                    </td>
                                    <td>
                                        {{$file->download}}
                                    </td>
                                    <td class="text-nowrap">
                                        <a href="{{route('admin_destroy', ['infinitum', $file->id])}}" data-toggle="tooltip" data-original-title="Удалить в корзину">
                                            <i class="mdi mdi-delete-forever"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $download_files->links() }}
                    </div>
                    <div class="tab-pane" id="profile3" role="tabpanel">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Наименование</th>
                                <th>Дата создания</th>
                                <th>Действия</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($delete_files as $file)
                                <tr>
                                    <td>
                                        {{$file->name}}
                                        <div>
                                            Инвестор:
                                            <a href="{{route('admin_show', ['users', $file->user->id])}}" target="_blank">
                                                {{$file->user->lastname.' '.$file->user->name.' '.$file->user->patronymic.' '}}
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        {{date('d.m.Y H:i:s', strtotime($file->created_at))}}
                                    </td>
                                    <td class="text-nowrap">
                                        <a href="{{route('admin_restore', ['infinitum', $file->id])}}" data-toggle="tooltip" data-original-title="Восстановить">
                                            <i class="mdi mdi-backup-restore"></i>
                                        </a>
                                        <a href="{{route('admin_delete', ['infinitum', $file->id])}}" data-toggle="tooltip" data-original-title="Удалить в корзину">
                                            <i class="mdi mdi-delete-forever"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $delete_files->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
