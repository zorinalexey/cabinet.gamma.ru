@extends('layouts.front_main')

@section('scripts')
    <script src="/js/select.js"></script>
@endsection

@section('content')
    <div class="content-body">
        <h1 class="main-heading">Авторизация в личном кабинете</h1>
        <div class="item item1">
            <h2 class="sub-heading">По номеру телефона</h2>
            <p class="item-description">
                {{$message}}
            </p>
            <div class="item-actions">
                <form method="post" action="{{route('auth')}}">
                    @csrf
                    <div class="item-actions">
                        @if ($button)
                            <input type="hidden" name="phone" value="{{$phone}}">
                            <input placeholder="Введите код из смс" class="input-default" name="code">
                            <button class="green-btn">{{$button}}</button>
                        @else
                            <a href="/" class="green-btn">Вернуться назад</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
        <div class="separator">
            <div class="separator-item"></div>
            <div class="separator-item">или</div>
            <div class="separator-item"></div>
        </div>
        <div class="item item1">
            <h2 class="sub-heading">Через госуслуги</h2>
            <p class="item-description">
                После нажатия на кнопку «Войти» вы будете переадресованы
                на сайт Госуслуги, в котором вам необходимо авторизоваться
            </p>
            <div class="item-actions">
                <img class="gosuslugi" src="/img/gosuslugi.svg" alt="">
                <a href="{{route('esia_auth')}}" class="green-btn">Войти</a>
            </div>
        </div>
    </div>
@stop

