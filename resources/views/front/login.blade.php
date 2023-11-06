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
                После того, как вы введете свой номер телефона, вам придёт смс
                с кодом, который необходимо ввести для входа в личный кабинет
            </p>
            <div class="item-actions">
                <form method="post" action="{{route('get_aut_code')}}">
                    @csrf
                    <div class="item-actions">
                        <input placeholder="Номер телефона" class="tel input-default" name="phone">
                        <button class="green-btn">Получить код</button>
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
                <a href="/esia/auth" class="green-btn">Войти</a>
            </div>
        </div>
    </div>
@stop
