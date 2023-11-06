@extends('layouts.cabinet')
@section('title', 'Все доступные к покупке фонды')

@section('content')
    <div class="content-body overflow-hidden">
        <div class="row">
            <div class="breadcrumbs">
                <a href="/cabinet" class="breadcrumbs-item">Личный кабинет</a>
                <div class="separator">|</div>
                <div class="breadcrumbs-item">Все фонды</div>
            </div>
        </div>
        <div class="row m-0">
            <div class="tabs">
                <div id="1" class="tab-item active">Все фонды</div>
                <div id="2" class="tab-item">Для квалифицированных</div>
                <div id="3" class="tab-item">Для неквалифицированных</div>
            </div>
        </div>
        <div class="row m-0">
            <div id="tab-content1" class="green-panels tab-content active">
                @foreach($all_user_funds as $fund)
                    @include('snippets.fund_info')
                @endforeach
            </div>
            <div id="tab-content2" class="green-panels tab-content">
                @foreach($qual_user_funds as $fund)
                    @include('snippets.fund_info')
                @endforeach
            </div>
            <div id="tab-content3" class="green-panels tab-content">
                @foreach($no_qual_user_funds as $fund)
                    @include('snippets.fund_info')
                @endforeach
            </div>
        </div>
    </div>
    <script src="/js/tabs.js"></script>
@stop
