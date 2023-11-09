@extends('layouts.admin')
@section('title', 'Все новости')

@section('bredcrumb')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">@yield('title')</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.main')}}">Админ-панель</a></li>
                    <li class="breadcrumb-item active">Новости</li>
                </ol>
                <a href="{{route('admin.post.create')}}" type="button" class="btn btn-info d-none d-lg-block m-l-15">
                    <i class="fa fa-plus-circle"></i>
                    Создать новый пост
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body p-b-0">
                    <h4 class="card-title">Новости</h4>
                </div>
                <ul class="nav nav-tabs customtab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#home2" role="tab">
                            <span class="hidden-xs-down">Активные новости</span>
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
                        @if($active_news->count() > 0)
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Заголовок</th>
                                    <th>Краткий текст</th>
                                    <th>Дата создания</th>
                                    <th>Дата редактирования</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($active_news as $post)
                                    <tr>
                                        <td>
                                            <a href="{{route('post', [$post->alias])}}" target="_blank">
                                                {{$post->title}}
                                            </a>
                                        </td>
                                        <td>{{$post->short_content()}}</td>
                                        <td>{{date('d.m.Y', strtotime($post->created_at))}}</td>
                                        <td>{{date('d.m.Y', strtotime($post->updated_at))}}</td>
                                        <td class="text-nowrap">
                                            <a href="{{route('admin_edit', ['news', $post->id])}}" data-toggle="tooltip"
                                               data-original-title="Изменить"><i class="fas fa-edit"></i> </a>
                                            <a href="{{route('admin_destroy', ['news', $post->id])}}"
                                               data-toggle="tooltip" data-original-title="Удалить в корзину"> <i
                                                    class="mdi mdi-delete-forever"></i> </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{ $active_news->links() }}
                        @else
                            Посты отсутствуют. Создайте <a href="{{route('admin.post.create')}}">новый пост</a>
                        @endif
                    </div>
                    <div class="tab-pane  p-20" id="profile2" role="tabpanel">
                        @if($delete_news->count() > 0)
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Заголовок</th>
                                    <th>Краткий текст</th>
                                    <th>Дата создания</th>
                                    <th>Дата редактирования</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($delete_news as $post)
                                    <tr>
                                        <td>
                                            <a href="{{route('post', [$post->alias])}}" target="_blank">
                                                {{$post->title}}
                                            </a>
                                        </td>
                                        <td>{{$post->short_content()}}</td>
                                        <td>{{date('d.m.Y', strtotime($post->created_at))}}</td>
                                        <td>{{date('d.m.Y', strtotime($post->updated_at))}}</td>
                                        <td class="text-nowrap">
                                            <a href="{{route('admin_restore', ['news', $post->id])}}"
                                               data-toggle="tooltip" data-original-title="Восстановить">
                                                <i class="mdi mdi-backup-restore"></i>
                                            </a>
                                            <a href="{{route('admin_delete', ['news', $post->id]).'#profile2'}}"
                                               data-toggle="tooltip" data-original-title="Удалить полностью">
                                                <i class="mdi mdi-delete-forever"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{ $delete_news->links() }}
                        @else
                            Удаленные посты отсутствуют
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
