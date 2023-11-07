@extends('layouts.front_main')
@section('title', 'Подписать документ '.$document->name)

@section('content')
    <div class="content-body content-documents">
        <h1 class="main-heading documents-heading">
            <a href="{{$document->path}}" target="_blank">{{$document->name}}</a>
        </h1>
        <div class="item item-center">
            <p class="item-description documents-description">
                Для подписания документов на ваш телефон будет отправлено смс с кодом, вводя который вы подтверждаете
                указанные вами сведения и подписываете документы
            </p>
            <div class="item-actions">
                <form method="POST">
                    @csrf
                    <input placeholder="Введите код из смс" type="text" name="sms_code" class="input-default">
                    <div class="actions-block">
                        <button class="green-btn">Отправить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
