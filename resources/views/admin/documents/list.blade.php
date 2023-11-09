@extends('layouts.admin');

@section('title', 'Документы инвесторов')

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
        <div class="col-md-12">
            <div class="card">
                <div class="card-body p-b-0">
                    <h4 class="card-title">@yield('title')</h4>
                </div>
                <ul class="nav nav-tabs customtab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#home2" role="tab">
                            <span class="hidden-xs-down">Активные документы</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#profile2" role="tab">
                            <span class="hidden-xs-down">Корзина</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="home2" role="tabpanel">
                        @if($active_docs->count() > 0)
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Название документа</th>
                                    <th>Статус</th>
                                    <th>Дата создания</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($active_docs as $doc)
                                    <tr>
                                        <td>
                                            <a href="{{$doc->path}}" target="_blank">{{$doc->name}}</a>
                                            <div>
                                                Документ инвестора :
                                                <a href="{{route('admin.user.show', $doc->user->id)}}" target="_blank">
                                                    {{$doc->user->lastname.' '.$doc->user->name.' '.$doc->user->patronymic}}
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            @if($doc->status === 'Документ подписан')
                                                <span style="color: green">
                                                    {{$doc->status}}
                                                    {{date('d.m.Y', strtotime($doc->updated_at))}}
                                                </span>
                                            @else
                                                <span style="color: red">{{$doc->status}}</span>
                                            @endif
                                        </td>
                                        <td>{{date('d.m.Y', strtotime($doc->created_at))}} </td>
                                        <td class="text-nowrap">
                                            <a href="{{route('admin.document.destroy', $doc->id)}}" data-toggle="tooltip" data-original-title="Удалить в корзину">
                                                <i class="mdi mdi-delete-forever"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{ $active_docs->links() }}
                        @else
                            Документы отсутствуют
                        @endif
                    </div>
                    <div class="tab-pane  p-20" id="profile2" role="tabpanel">
                        @if($delete_docs->count() > 0)
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Название документа</th>
                                    <th>Статус</th>
                                    <th>Дата удаления</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($delete_docs as $doc)
                                    <tr>
                                        <td>
                                            <a href="{{route('admin.document.list')}}">{{$doc->name}}</a>
                                            <div>
                                                Документ инвестора {{$doc->user->lastname.' '.$doc->user->name.' '.$doc->user->patronymic}}
                                            </div>
                                        </td>
                                        <td>
                                            {{$doc->status}}
                                        </td>
                                        <td>{{date('d.m.Y', strtotime($doc->deleted_at))}} </td>
                                        <td class="text-nowrap">
                                            <a href="{{route('admin.document.restore', $doc->id)}}"
                                               data-toggle="tooltip" data-original-title="Восстановить">
                                                <i class="mdi mdi-backup-restore"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{ $delete_docs->links() }}
                        @else
                            Удаленные документы отсутствуют
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
