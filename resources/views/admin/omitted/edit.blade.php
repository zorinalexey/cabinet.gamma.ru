@extends('layouts.admin')
@section('title', $omitted->name)
@section('scripts')
    <script src="/js/select.js"></script>
    <script src="/js/jquery.maskedinput.min.js"></script>
    <script>
        count = {{$count}};
        $(function () {
            $('input[name="start_date"]').mask("99.99.9999 99:99");
        });
        $(function () {
            $('input[name="end_date"]').mask("99.99.9999 99:99");
        });
        $(function () {
            $('input[name="total_date"]').mask("99.99.9999 99:99");
        });

        function remove(id) {
            count--;
            document.getElementById('count-' + id).remove();
            let url = '/admin/voting/delete/' + id;
            let data = {
                omitted: {!! json_encode($omitted) !!}
            };
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json;charset=utf-8',
                    'X-CSRF-TOKEN': document.getElementsByName('csrf-token')[0].getAttribute('content')
                },
                body: JSON.stringify(data)
            });
        }

        function addVoting() {
            count++;
            let block = document.getElementById('votings');
            let newBlock = document.createElement('div');
            newBlock.className = "card-body";
            newBlock.setAttribute('id', 'count-' + count);
            newBlock.innerHTML = '\n\
                <h5>' + count + ' вопрос голосования</h5>\n\
                <div class="row pt-3">\n\
                    <div class="col-md-12">\n\
                        <label class="control-label">' + count + ' вид сделки</label>\n\
                        <textarea type="text" class="form-control" name="votings[' + count + '][type_transaction]" required></textarea>\n\
                        <small class="form-control-feedback">Введите вид сделки</small>\n\
                    </div>\n\
                    <div class="col-md-6">\n\
                        <label class="control-label">Прочие условия голосования</label>\n\
                        <textarea class="form-control" name="votings[' + count + '][other_conditions]"></textarea>\n\
                        <small class="form-control-feedback">Укажите прочие условия о проведении голосования</small>\n\
                    </div>\n\
                    <div class="col-md-6"><!-- comment -->\n\
                        <label class="control-label">Стороны по сделке</label>\n\
                        <textarea type="text" class="form-control" name="votings[' + count + '][parties_transaction]" required></textarea>\n\
                        <small class="form-control-feedback">Введите стороны по сделке</small>\n\
                    </div>\n\
                    <div class="col-md-6"><!-- comment -->\n\
                        <label class="control-label">Предмет сделки</label>\n\
                        <textarea type="text" class="form-control" name="votings[' + count + '][subject_transaction]"  required></textarea>\n\
                        <small class="form-control-feedback">Введите предмет сделки</small>\n\
                    </div>\n\
                    <div class="col-md-6"><!-- comment -->\n\
                        <label class="control-label">Цена сделки</label>\n\
                        <input type="text" class="form-control" name="votings[' + count + '][cost_transaction]" value="" required>\n\
                        <small class="form-control-feedback">Введите стоимость сделки</small>\n\
                    </div>\n\
                    <div class="col-md-4">\n\
                        <a href="javascript:void(0)" onclick="remove(\'count-' + count + '\');" title="Удалить"> <i class="fas fa-trash"></i></a>\n\
                    </div>\n\
                </div><hr>';
            block.append(newBlock);
        }
    </script>
@endsection
@section('bredcrumb')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">@yield('title')</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin_main')}}">Админ-панель</a></li>
                    <li class="breadcrumb-item"><a href="{{route('admin_index', ['omitted'])}}">Инвестиционный
                            комитет</a></li>
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
                    <form action="{{route('admin_update', ['omitted', $omitted->id])}}" method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="form-body">
                            <h3 class="card-title">{{$omitted->name}}</h3>
                            <hr>
                            <div class="row p-t-20">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Название голосования</label>
                                        <input type="text" class="form-control" value="{{$omitted->name}}" name="name" placeholder="Введите название голосования" required>
                                        <small class="form-control-feedback">Введите название голосования</small>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Решение о проведении голосования</label>
                                        <input type="file" class="form-control" name="file" placeholder="Выберите файл решения о проведении голосования">
                                        <small class="form-control-feedback">Выберите файл решения о проведении голосования</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Фонд голосования</label>
                                        <select class="form-control custom-select" name="fund_id" required>
                                            <option>---</option>
                                            @foreach($funds as $fund)
                                                <option @if($fund->id === $omitted->fund_id) selected @endif value="{{$fund->id}}">{{$fund->name}}</option>
                                            @endforeach
                                        </select>
                                        <small class="form-control-feedback">Выберите фонд голосования</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Дата начала голосования</label>
                                        <input type="text" class="form-control" value="{{date('d.m.Y H:i', strtotime($omitted->start_date))}}" name="start_date" placeholder="Дата начала голосования" required>
                                        <small class="form-control-feedback">Введите дату начала голосования (меньше текущей даты)</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Дата окончания голосования</label>
                                        <input type="text" class="form-control" value="{{date('d.m.Y H:i', strtotime($omitted->end_date))}}" name="end_date" placeholder="Дата окончания голосования" required>
                                        <small class="form-control-feedback">Введите дату подведения итогов голосования (больше или равна дате окончания голосования)</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Дата подведения итогов голосования</label>
                                        <input type="text" class="form-control" value="{{date('d.m.Y H:i', strtotime($omitted->total_date))}}" name="total_date" placeholder="Дата подведения итогов голосования" required>
                                        <small class="form-control-feedback">Введите дату подведения итогов голосования</small>
                                    </div>
                                </div>
                            </div>
                            <h3 class="card-title">Вопросы голосования</h3>
                            <hr>
                            @php
                                $count = 0;
                            @endphp
                            <div class="row p-t-20" id="votings">
                                @foreach($omitted->votings as $voting)
                                    @php
                                        $count++;
                                    @endphp
                                    <div class="card-body" id="count-{{$voting->id}}">
                                        <h5>{{$count}} вопрос голосования</h5>
                                        <div class="row pt-3">
                                            <div class="col-12">
                                                <label class="control-label">Вид сделки</label>
                                                <textarea type="text" class="form-control" name="votings[{{$voting->id}}][type_transaction]" required>{{$voting->type_transaction}}</textarea>
                                                <small class="form-control-feedback">Введите вид сделки</small>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="control-label">Прочие условия голосования</label>
                                                <textarea type="text"  class="form-control" name="votings[{{$voting->id}}][other_conditions]">{{$voting->other_conditions}}</textarea>
                                                <small class="form-control-feedback">Укажите прочие условия о проведении голосования</small>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="control-label">Стороны по сделке</label>
                                                <textarea type="text" class="form-control" name="votings[{{$voting->id}}][parties_transaction]" required>{{$voting->parties_transaction}}</textarea>
                                                <small class="form-control-feedback">Введите стороны по сделке</small>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="control-label">Предмет сделки</label>
                                                <textarea type="text" class="form-control" name="votings[{{$voting->id}}][subject_transaction]" required>{{$voting->subject_transaction}}</textarea>
                                                <small class="form-control-feedback">Введите предмет сделки</small>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="control-label">Цена сделки</label>
                                                <textarea type="text" class="form-control" name="votings[{{$voting->id}}][cost_transaction]" required>{{$voting->cost_transaction}}</textarea>
                                                <small class="form-control-feedback">Введите стоимость сделки</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <a href="javascript:void(0)" onclick="remove({{$voting->id}});"
                                               title="Удалить"> <i class="fas fa-trash"></i></a>
                                        </div>
                                    </div>
                                    <hr>
                                @endforeach
                            </div>
                            <hr>
                            <div class="form-actions">
                                <button type="button" class="btn btn-info" onclick="addVoting();">Добавить вопрос
                                </button>
                            </div>
                        </div>
                        <hr>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Сохранить</button>
                            <button type="reset" class="btn btn-info"> Очистить все</button>
                            <a href="{{route('admin_index', ['omitted'])}}" type="button"
                               class="btn btn-inverse">Назад</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
