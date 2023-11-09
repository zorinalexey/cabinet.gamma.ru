@extends('layouts.admin')
@section('title', 'Создать новоое голосование')
@section('scripts')
    <script src="/js/select.js"></script>
    <script src="/js/jquery.maskedinput.min.js"></script>
    <script>
        count = 1;

        function remove(block) {
            count--;
            document.getElementById(block).remove();
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
                        <textarea type="text" class="form-control" name="votings[][type_transaction]" required></textarea>\n\
                        <small class="form-control-feedback">Введите вид сделки</small>\n\
                    </div>\n\
                    <div class="col-md-6">\n\
                        <label class="control-label">Прочие условия голосования</label>\n\
                        <textarea class="form-control" name="votings[][other_conditions]"></textarea>\n\
                        <small class="form-control-feedback">Укажите прочие условия о проведении голосования</small>\n\
                    </div>\n\
                    <div class="col-md-6"><!-- comment -->\n\
                        <label class="control-label">Стороны по сделке</label>\n\
                        <textarea type="text" class="form-control" name="votings[][parties_transaction]" required></textarea>\n\
                        <small class="form-control-feedback">Введите стороны по сделке</small>\n\
                    </div>\n\
                    <div class="col-md-6"><!-- comment -->\n\
                        <label class="control-label">Предмет сделки</label>\n\
                        <textarea type="text" class="form-control" name="votings[][subject_transaction]" required></textarea>\n\
                        <small class="form-control-feedback">Введите предмет сделки</small>\n\
                    </div>\n\
                    <div class="col-md-6"><!-- comment -->\n\
                        <label class="control-label">Цена сделки</label>\n\
                        <textarea type="text" class="form-control" name="votings[][cost_transaction]" required></textarea>\n\
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
                    <li class="breadcrumb-item"><a href="{{route('admin.main')}}">Админ-панель</a></li>
                    <li class="breadcrumb-item"><a href="{{route('admin.omitted.list')}}">Инвестиционный
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
                    <form action="{{route('admin.omitted.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-body">
                            <h3 class="card-title">Новое голосование</h3>
                            <hr>
                            <div class="row p-t-20">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Название голосования</label>
                                        <input type="text" class="form-control" name="name" placeholder="Введите название голосования" required>
                                        <small class="form-control-feedback">Введите название голосования</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Решение о проведении голосования</label>
                                        <input type="file" class="form-control" name="file" placeholder="Выберите файл решения о проведении голосования" required>
                                        <small class="form-control-feedback">Выберите файл решения о проведении голосования</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Фонд голосования</label>
                                        <select class="form-control custom-select" name="fund_id" required>
                                            <option>---</option>
                                            @foreach($funds as $fund)
                                                <option value="{{$fund->id}}">{{$fund->name}}</option>
                                            @endforeach
                                        </select>
                                        <small class="form-control-feedback">Выберите фонд голосования</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Дата начала голосования</label>
                                        <input type="datetime-local" class="form-control" name="start_date" value="{{date('Y-m-d\TH:i')}}" required>
                                        <small class="form-control-feedback">Введите дату начала голосования (меньше текущей даты)</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Дата окончания голосования</label>
                                        <input type="datetime-local" class="form-control" name="end_date" value="{{date('Y-m-d\TH:i', strtotime('+10 days'))}}" required>
                                        <small class="form-control-feedback">Введите дату окончания голосования (больше даты начала голосования и текущей даты)</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Дата подведения итогов голосования</label>
                                        <input type="datetime-local" class="form-control" name="total_date" value="{{date('Y-m-d\TH:i', strtotime('+12 days'))}}" required>
                                        <small class="form-control-feedback">Введите дату подведения итогов голосования (больше или равна дате окончания голосования)</small>
                                    </div>
                                </div>
                            </div>
                            <h3 class="card-title">Вопросы голосования</h3>
                            <hr>
                            <div class="row p-t-20" id="votings">
                                <div class="card-body">
                                    <h5>1 вопрос голосования</h5>
                                    <div class="row pt-3">
                                        <div class="col-12">
                                            <label class="control-label">Вид сделки</label>
                                            <textarea type="text" class="form-control" name="votings[][type_transaction]" required></textarea>
                                            <small class="form-control-feedback">Введите вид сделки</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="control-label">Прочие условия голосования</label>
                                            <textarea type="text" class="form-control" name="votings[][other_conditions]"></textarea>
                                            <small class="form-control-feedback">Укажите прочие условия о проведении голосования</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="control-label">Стороны по сделке</label>
                                            <textarea type="text" class="form-control" name="votings[][parties_transaction]" required></textarea>
                                            <small class="form-control-feedback">Введите стороны по сделке</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="control-label">Предмет сделки</label>
                                            <textarea type="text" class="form-control" name="votings[][subject_transaction]" required></textarea>
                                            <small class="form-control-feedback">Введите предмет сделки</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="control-label">Цена сделки</label>
                                            <textarea type="text" class="form-control" name="votings[][cost_transaction]" required></textarea>
                                            <small class="form-control-feedback">Введите стоимость сделки</small>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            </div>
                            <div class="form-actions">
                                <button type="button" class="btn btn-info" onclick="addVoting();">Добавить вопрос
                                </button>
                            </div>
                        </div>
                        <hr>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Сохранить</button>
                            <button type="reset" class="btn btn-info"> Очистить все</button>
                            <a href="{{url()->previous()}}" type="button" class="btn btn-inverse">Назад</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
