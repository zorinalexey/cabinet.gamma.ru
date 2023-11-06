@section('title', 'Список опросов')
@extends('layouts.admin')
@section('bredcrumb')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">@yield('title')</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin_main')}}">Админ-панель</a></li>
                    <li class="breadcrumb-item active">Инвестиционный комитет</li>
                </ol>
                <a href="{{route('admin_create', ['omitted'])}}" type="button"
                   class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i> Создать новый
                    опрос</a>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var activ = null;

        function viewUserInfo(id) {
            let block = document.getElementById('omitted_info_' + id);
            if (!activ) {
                activ = block;
                activ.style.display = 'block';
            } else if (activ !== block) {
                activ.style.display = 'none';
                activ = block;
                activ.style.display = '';
            } else {
                activ.style.display = 'none';
                activ = null;
            }
        }

    </script>
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
                        <a class="nav-link active" data-toggle="tab" href="#tab1" role="tab">
                            <span class="hidden-xs-down">Активные опросы</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab2" role="tab">
                            <span class="hidden-xs-down">Корзина</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab1" role="tabpanel">
                        @if($active_omitteds->count() > 0)
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Наименование голосования</th>
                                    <th>Фонд голосования</th>
                                    <th>Статус голосования</th>
                                    <th>Дата начала голосования</th>
                                    <th>Дата окончания голосования</th>
                                    <th>Дата подведения итогов голосования</th>
                                    <th>Дата создания (редактирования)</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($active_omitteds as $omitted)
                                    <tr onclick="viewUserInfo({{$omitted->id}})" style="cursor: pointer">
                                        <td>
                                            {{$omitted->name}}
                                        </td>
                                        <td>
                                            <a href="{{route('admin_show', ['funds', $omitted->fund_id])}}" target="_blank">
                                                {{$omitted->fund->name}}
                                            </a>
                                        </td>
                                        <td>
                                            {{$omitted->status()}}
                                        </td>
                                        <td>
                                            {{date('d.m.Y H:i:s', strtotime($omitted->start_date))}}
                                        </td>
                                        <td>
                                            @if($omitted->end_date)
                                            {{date('d.m.Y H:i:s', strtotime($omitted->end_date))}}
                                            @else
                                                01.01.2099 00.00.00
                                            @endif
                                        </td>
                                        <td>
                                            @if($omitted->total_date)
                                                {{date('d.m.Y H:i:s', strtotime($omitted->total_date))}}
                                            @else
                                                01.01.2099 00.00.00
                                            @endif
                                        </td>
                                        <td>

                                            @if($omitted->updated_at)
                                                {{date('d.m.Y H:i:s', strtotime($omitted->updated_at))}}
                                            @else
                                                01.01.1970 00.00.00
                                            @endif
                                        </td>
                                        <td class="text-nowrap">
                                            <a href="{{route('admin_edit', ['omitted', $omitted->id])}}"
                                               data-toggle="tooltip" data-original-title="Изменить"><i
                                                    class="fas fa-edit"></i> </a>
                                            <a href="{{route('admin_destroy', ['omitted', $omitted->id])}}"
                                               data-toggle="tooltip" data-original-title="Удалить в корзину"> <i
                                                    class="mdi mdi-delete-forever"></i> </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" style="padding: 0;">
                                            <div style="display: none; padding: 1rem;"
                                                 id="omitted_info_{{$omitted->id}}"
                                                 onclick="viewUserInfo({{$omitted->id}})">
                                                <div class="col-md-4">
                                                    Всего вопросов в голосовании : {{count($omitted->votings)}}
                                                </div>
                                                <div>
                                                    <h5>Вопросы поставленные на голосовании</h5>
                                                    @foreach($omitted->votings as $voting)
                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-6">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <div class="d-flex flex-row">
                                                                            <div class="m-l-10 align-self-center">
                                                                                <h5 class="text-muted m-b-0 text-center">
                                                                                    Вид сделки</h5>
                                                                                <h3 class="m-b-0 text-center">{{$voting->type_transaction}}</h3>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-3 col-md-6">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <div class="d-flex flex-row">
                                                                            <div class="m-l-10 align-self-center">
                                                                                <h5 class="text-muted m-b-0 text-center">
                                                                                    Стороны по сделке</h5>
                                                                                <h3 class="m-b-0 text-center">{{$voting->parties_transaction}}</h3>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-3 col-md-6">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <div class="d-flex flex-row">
                                                                            <div class="m-l-10 align-self-center">
                                                                                <h5 class="text-muted m-b-0 text-center">
                                                                                    Предмет сделки</h5>
                                                                                <h3 class="m-b-0 text-center">{{$voting->subject_transaction}}</h3>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-3 col-md-6">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <div class="d-flex flex-row">
                                                                            <div class="m-l-10 align-self-center">
                                                                                <h5 class="text-muted m-b-0 text-center">
                                                                                    Цена сделки</h5>
                                                                                <h3 class="m-b-0 text-center">{{$voting->cost_transaction}}</h3>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                    @endforeach
                                                </div>
                                                <h4>Список проголосовавших</h4>
                                                @foreach($omitted->getUsers() as $user)
                                                    @php
                                                        $omittedDocument = $user->getOmittedDocument($omitted);
                                                        $percents = $omitted->getAnswersPercent($user->answersOmitted($omitted));
                                                    @endphp
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-6">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <div class="d-flex flex-row">
                                                                        <div class="m-l-10 align-self-center">
                                                                            <a href="{{route('admin_show', ['users', $user->id])}}"
                                                                               target="_blank">
                                                                                {{$user->lastname}} {{$user->name}} {{$user->patronymic}}
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-6">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <div class="d-flex flex-row">
                                                                        <div class="m-l-10 align-self-center">
                                                                            @php
                                                                                if($omittedDocument){
                                                                                    echo '<a href="'.$omittedDocument->path.'" target="_blank">Бюллетень голосования</a>';
                                                                                    $status = 'Бюллетень не подписан';
                                                                                    if($omittedDocument->sign_status){
                                                                                        $status = 'Бюллетень подписан';
                                                                                    }
                                                                                    echo '<div class="text-center">('.$status.')</div>';
                                                                                }
                                                                            @endphp
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-6">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <div class="d-flex flex-row">
                                                                        <div class="m-l-10 align-self-center">
                                                                            <h5 class="text-muted m-b-0 text-center"> %
                                                                                ответов "За"</h5>
                                                                            <h3 class="m-b-0 text-center">{{$percents['true']}}
                                                                                % </h3>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-6">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <div class="d-flex flex-row">
                                                                        <div class="m-l-10 align-self-center">
                                                                            <h5 class="text-muted m-b-0 text-center"> %
                                                                                ответов "Против"</h5>
                                                                            <h3 class="m-b-0 text-center">{{$percents['false']}}
                                                                                %</h3>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{ $active_omitteds->links() }}
                        @else
                            Опросы отсутствуют. Создайте <a href="{{route('admin_create', ['omitted'])}}">новый
                                опрос</a>
                        @endif
                    </div>
                    <div class="tab-pane  p-20" id="tab2" role="tabpanel">
                        @if($delete_omitteds->count() > 0)
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Наименование голосования</th>
                                    <th>Фонд голосования</th>
                                    <th>Статус голосования</th>
                                    <th>Дата начала голосования</th>
                                    <th>Дата окончания голосования</th>
                                    <th>Дата подведения итогов голосования</th>
                                    <th>Дата удаления</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($delete_omitteds as $omitted)
                                    <tr>
                                        <td>
                                            <a href="{{route('admin_show', ['omitted', $omitted->id])}}">
                                                {{$omitted->name}}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{route('admin_show', ['funds', $omitted->fund_id])}}">{{$omitted->fund->name}}</a>
                                        </td>
                                        <td>
                                            {{$omitted->status()}}
                                        </td>
                                        <td>
                                            {{date('d.m.Y H:i:s', strtotime($omitted->start_date))}}
                                        </td>
                                        <td>
                                            {{date('d.m.Y H:i:s', strtotime($omitted->end_date))}}
                                        </td>
                                        <td>
                                            {{date('d.m.Y H:i:s', strtotime($omitted->total_date))}}
                                        </td>
                                        <td>
                                            {{date('d.m.Y H:i:s', strtotime($omitted->deleted_at))}}
                                        </td>
                                        <td class="text-nowrap">
                                            <a href="{{route('admin_restore', ['omitted', $omitted->id])}}"
                                               data-toggle="tooltip" data-original-title="Восстановить">
                                                <i class="mdi mdi-backup-restore"></i>
                                            </a>
                                            <a href="{{route('admin_delete', ['omitted', $omitted->id]).'#tab2'}}"
                                               data-toggle="tooltip" data-original-title="Удалить полностью">
                                                <i class="mdi mdi-delete-forever"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{ $delete_omitteds->links() }}
                        @else
                            Удаленные опросы отсутствуют
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
