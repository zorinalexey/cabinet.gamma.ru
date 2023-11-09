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
                    <li class="breadcrumb-item"><a href="{{route('admin.main')}}">Админ-панель</a></li>
                    <li class="breadcrumb-item active">Инвестиционный комитет</li>
                </ol>
                <a href="{{route('admin.omitted.create')}}" type="button"
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
                                            <a href="{{route('admin.fund.show', $omitted->fund_id)}}" target="_blank">
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
                                            <a href="{{route('admin.omitted.edit', $omitted->id)}}"
                                               data-toggle="tooltip" data-original-title="Изменить"><i
                                                    class="fas fa-edit"></i> </a>
                                            <a href="{{route('admin.omitted.destroy', $omitted->id)}}"
                                               data-toggle="tooltip" data-original-title="Удалить в корзину"> <i
                                                    class="mdi mdi-delete-forever"></i> </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="8" style="padding: 0;">
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
                                                                            <a href="{{route('admin.user.show', $user->id)}}"
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
                                                @if($protocol = $omitted->protocol)
                                                    <a href="{{$protocol->docx}}" target="_blank" title="Скачать в формате Word">
                                                        <button type="button" class="btn btn-success doc-download">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-word" viewBox="0 0 16 16">
                                                                <path d="M4.879 4.515a.5.5 0 0 1 .606.364l1.036 4.144.997-3.655a.5.5 0 0 1 .964 0l.997 3.655 1.036-4.144a.5.5 0 0 1 .97.242l-1.5 6a.5.5 0 0 1-.967.01L8 7.402l-1.018 3.73a.5.5 0 0 1-.967-.01l-1.5-6a.5.5 0 0 1 .364-.606z"/>
                                                                <path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/>
                                                            </svg> Word
                                                        </button>
                                                    </a>
                                                    <a href="{{$protocol->pdf}}" target="_blank" title="Скачать в формате PDF">
                                                        <button type="button" class="btn btn-success doc-download">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-pdf" viewBox="0 0 16 16">
                                                                <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                                                                <path d="M4.603 14.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.697 19.697 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.712 5.712 0 0 1-.911-.95 11.651 11.651 0 0 0-1.997.406 11.307 11.307 0 0 1-1.02 1.51c-.292.35-.609.656-.927.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361.01.022.02.036.026.044a.266.266 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.71 12.71 0 0 1 1.01-.193 11.744 11.744 0 0 1-.51-.858 20.801 20.801 0 0 1-.5 1.05zm2.446.45c.15.163.296.3.435.41.24.19.407.253.498.256a.107.107 0 0 0 .07-.015.307.307 0 0 0 .094-.125.436.436 0 0 0 .059-.2.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.876 3.876 0 0 0-.612-.053zM8.078 7.8a6.7 6.7 0 0 0 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822.024.111.054.227.09.346z"/>
                                                            </svg>
                                                            PDF
                                                        </button>
                                                    </a>
                                                    <a href="{{route('admin.omitted.protocol.gen', $omitted->id)}}"><button type="button" class="btn btn-success">Сформировать новый протокол голосования</button></a>
                                                @else
                                                    <a href="{{route('admin.omitted.protocol.gen', $omitted->id)}}"><button type="button" class="btn btn-success">Сформировать протокол голосования</button></a>
                                                @endif
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
