<div id="tab-content3" class="tab-content user-data-list banks-tables">
    <div class="row flex-row mt-50">
        <h2 class="sub-heading">Рублевые счета</h2>
        <button data-toggle="#addRubles" class="btn-green popup-btn"> Добавить рублёвый банковский счёт</button>
    </div>
    <div class="row bank-table">
        @if($user->ruble_accounts->count() > 0)
            <div class="row m-0 flex-row underline">
                <div>Получатель</div>
                <div>Расчётный Счёт</div>
                <div>Банк</div>
                <div>Страна</div>
                <div>Действия</div>
            </div>
            @foreach($user->ruble_accounts as  $account)
                <div class="row m-0 flex-row underline">
                    <div>{{$account->payment_recipient}}</div>
                    <div>{{$account->payment_account}}</div>
                    <div>{{$account->bank_name}}</div>
                    <div>{{$account->bank_country}}</div>
                    <div class="actions">
                        <a data-toggle="#editRubles_{{$account->id}}" class="green popup-btn">
                            <svg width="22" height="22" viewBox="0 0 14 14" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M12.9227 8.74229C12.7301 8.74229 12.574 8.89839 12.574 9.091V12.1871C12.5733 12.7647 12.1054 13.2327 11.5278 13.2332H1.74353C1.16598 13.2327 0.698091 12.7647 0.69741 12.1871V3.10022C0.698091 2.52281 1.16598 2.05478 1.74353 2.0541H4.83965C5.03225 2.0541 5.18835 1.898 5.18835 1.70539C5.18835 1.51293 5.03225 1.35669 4.83965 1.35669H1.74353C0.781045 1.35778 0.0010897 2.13773 0 3.10022V12.1873C0.0010897 13.1497 0.781045 13.9297 1.74353 13.9308H11.5278C12.4903 13.9297 13.2703 13.1497 13.2714 12.1873V9.091C13.2714 8.89839 13.1153 8.74229 12.9227 8.74229Z"
                                    fill="#A7CF43"></path>
                                <path
                                    d="M13.1321 0.459617C12.5193 -0.153206 11.5257 -0.153206 10.9129 0.459617L4.6918 6.68073C4.64916 6.72337 4.61838 6.77622 4.6023 6.83425L3.78421 9.78775C3.75056 9.90885 3.78475 10.0385 3.87356 10.1275C3.96251 10.2163 4.09219 10.2505 4.21328 10.217L7.16679 9.39873C7.22481 9.38266 7.27766 9.35187 7.3203 9.30924L13.5413 3.08798C14.1531 2.47475 14.1531 1.48203 13.5413 0.8688L13.1321 0.459617ZM5.45159 6.90739L10.5431 1.81575L12.1851 3.4578L7.0935 8.54944L5.45159 6.90739ZM5.12359 7.56557L6.43546 8.87758L4.62083 9.38034L5.12359 7.56557ZM13.0482 2.59489L12.6784 2.96471L11.0362 1.32253L11.4061 0.952707C11.7465 0.612311 12.2985 0.612311 12.6389 0.952707L13.0482 1.36189C13.388 1.7027 13.388 2.25422 13.0482 2.59489Z"
                                    fill="#A7CF43"></path>
                            </svg>
                        </a>
                        <a class="green" href="{{route('drop_ruble_account', $account->id)}}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="#A7CF43" viewBox="0 0 24 24" width="24px"
                                 height="24px">
                                <path
                                    d="M 10 2 L 9 3 L 3 3 L 3 5 L 4.109375 5 L 5.8925781 20.255859 L 5.8925781 20.263672 C 6.023602 21.250335 6.8803207 22 7.875 22 L 16.123047 22 C 17.117726 22 17.974445 21.250322 18.105469 20.263672 L 18.107422 20.255859 L 19.890625 5 L 21 5 L 21 3 L 15 3 L 14 2 L 10 2 z M 6.125 5 L 17.875 5 L 16.123047 20 L 7.875 20 L 6.125 5 z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            @endforeach
        @else
            <div class="row m-0 flex-row underline">
                Рублевые счета отсутствуют.
                Добавьте минимум 1 рублевый счет
            </div>
        @endif
    </div>
<!--
    <div class="row flex-row">
        <h2 class="sub-heading">Валютные счета</h2>
        <button data-toggle="#addCurrency" class="btn-green popup-btn"> Добавить валютный банковский счёт</button>
    </div>
    <div class="row bank-table">
        @if($user->currency_accounts->count() > 0)
            <div class="row m-0 flex-row underline">
                <div>Получатель</div>
                <div>Расчётный Счёт</div>
                <div>Банк</div>
                <div>Страна</div>
                <div>Действия</div>
            </div>
            @foreach($user->currency_accounts as  $account)
                <div class="row m-0 flex-row underline">
                    <div>{{$account->pay_beneficiary}}</div>
                    <div>{{$account->account_beneficiary_bank}}</div>
                    <div>{{$account->cor_bank}}</div>
                    <div>{{$account->country_bank}}</div>
                    <div class="actions">
                        <a data-toggle="#editCurrency_{{$account->id}}" class="green popup-btn">
                            <svg width="22" height="22" viewBox="0 0 14 14" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M12.9227 8.74229C12.7301 8.74229 12.574 8.89839 12.574 9.091V12.1871C12.5733 12.7647 12.1054 13.2327 11.5278 13.2332H1.74353C1.16598 13.2327 0.698091 12.7647 0.69741 12.1871V3.10022C0.698091 2.52281 1.16598 2.05478 1.74353 2.0541H4.83965C5.03225 2.0541 5.18835 1.898 5.18835 1.70539C5.18835 1.51293 5.03225 1.35669 4.83965 1.35669H1.74353C0.781045 1.35778 0.0010897 2.13773 0 3.10022V12.1873C0.0010897 13.1497 0.781045 13.9297 1.74353 13.9308H11.5278C12.4903 13.9297 13.2703 13.1497 13.2714 12.1873V9.091C13.2714 8.89839 13.1153 8.74229 12.9227 8.74229Z"
                                    fill="#A7CF43"></path>
                                <path
                                    d="M13.1321 0.459617C12.5193 -0.153206 11.5257 -0.153206 10.9129 0.459617L4.6918 6.68073C4.64916 6.72337 4.61838 6.77622 4.6023 6.83425L3.78421 9.78775C3.75056 9.90885 3.78475 10.0385 3.87356 10.1275C3.96251 10.2163 4.09219 10.2505 4.21328 10.217L7.16679 9.39873C7.22481 9.38266 7.27766 9.35187 7.3203 9.30924L13.5413 3.08798C14.1531 2.47475 14.1531 1.48203 13.5413 0.8688L13.1321 0.459617ZM5.45159 6.90739L10.5431 1.81575L12.1851 3.4578L7.0935 8.54944L5.45159 6.90739ZM5.12359 7.56557L6.43546 8.87758L4.62083 9.38034L5.12359 7.56557ZM13.0482 2.59489L12.6784 2.96471L11.0362 1.32253L11.4061 0.952707C11.7465 0.612311 12.2985 0.612311 12.6389 0.952707L13.0482 1.36189C13.388 1.7027 13.388 2.25422 13.0482 2.59489Z"
                                    fill="#A7CF43"></path>
                            </svg>
                        </a>
                        <a class="green" href="{{route('drop_currency_account', $account->id)}}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="#A7CF43" viewBox="0 0 24 24" width="24px"
                                 height="24px">
                                <path
                                    d="M 10 2 L 9 3 L 3 3 L 3 5 L 4.109375 5 L 5.8925781 20.255859 L 5.8925781 20.263672 C 6.023602 21.250335 6.8803207 22 7.875 22 L 16.123047 22 C 17.117726 22 17.974445 21.250322 18.105469 20.263672 L 18.107422 20.255859 L 19.890625 5 L 21 5 L 21 3 L 15 3 L 14 2 L 10 2 z M 6.125 5 L 17.875 5 L 16.123047 20 L 7.875 20 L 6.125 5 z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            @endforeach
        @else
            <div class="row m-0 flex-row underline">
                Валютные счета отсутствуют.
            </div>
        @endif
    </div>
-->
</div>
