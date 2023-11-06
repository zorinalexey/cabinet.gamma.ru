<!--
<div id="addCurrency" class="popup-wrapper">
    <form class="modal-body" method="POST" action="{{route('add_currency_account')}}">
        @csrf
        <input type="hidden" name="user_id" value="{{$user->id}}">
        <div class="form-block">
            <span class="black">Валюта:</span>
            <label>
                <input type="text" name="currency" class="input-default">
            </label>
        </div>
        <div class="form-block">
            <span class="black">Банк-корреспондент (на английском языке):</span>
            <label>
                <input type="text" name="currency_cor_bank" class="input-default">
            </label>
        </div>
        <div class="form-block">
            <span class="black">Страна нахождения банка-корреспондента:</span>
            <label>
                <input type="text" name="currency_country_bank" class="input-default">
            </label>
        </div>
        <div class="form-block">
            <span class="black">Город нахождения банка-корреспондента (на английском языке):</span>
            <label>
                <input type="text" name="currency_city_bank" class="input-default">
            </label>
        </div>
        <div class="form-block">
            <span class="black">SWIFT банка-корреспондента:</span>
            <label>
                <input type="text" name="currency_swift_bank" class="input-default">
            </label>
        </div>
        <div class="form-block">
            <span class="black">Номер счета в банке-корреспонденте:</span>
            <label>
                <input type="text" name="currency_account_number_cor_bank" class="input-default">
            </label>
        </div>
        <div class="form-block">
            <span class="black">Наименование банка бенефициара (на английском языке):</span>
            <label>
                <input type="text" name="currency_beneficiary_name_bank" class="input-default">
            </label>
        </div>
        <div class="form-block">
            <span class="black">Страна нахождения Банка бенефициара:</span>
            <label>
                <input type="text" name="currency_beneficiary_country_bank" class="input-default">
            </label>
        </div>
        <div class="form-block">
            <span class="black">Город нахождения Банка бенефициара (на английском языке):</span>
            <label>
                <input type="text" name="currency_beneficiary_city_bank" class="input-default">
            </label>
        </div>
        <div class="form-block">
            <span class="black">Счет бенефициара платежа:</span>
            <label>
                <input type="text" name="currency_account_beneficiary_bank" class="input-default">
            </label>
        </div>
        <div class="form-block">
            <span class="black">ИНН банка</span>
            <label>
                <input type="text" name="currency_tin_bank" class="input-default">
            </label>
        </div>
        <div class="form-block">
            <span class="black">Корреспондентский счёт:</span>
            <label>
                <input type="text" name="currency_cor_account_bank" class="input-default">
            </label>
        </div>
        <div class="form-block">
            <span class="black">Бенефициар платежа (на английском языке):</span>
            <label>
                <input type="text" name="currency_pay_beneficiary" class="input-default">
            </label>
        </div>
        <div class="form-block">
            <span class="black">Адрес бенефициара платежа (на английском языке):</span>
            <label>
                <input type="text" name="currency_pay_address" class="input-default">
            </label>
        </div>
        <div class="form-block center">
            <button class="btn-green">Добавить</button>
        </div>
    </form>
</div>


@foreach($user->currency_accounts as $account)
    <div id="editCurrency_{{$account->id}}" class="popup-wrapper">
        <form class="modal-body" method="POST" action="{{route('edit_currency_account', $account->id)}}">
            @csrf
            <input type="hidden" name="user_id" value="{{$user->id}}">
            <div class="form-block">
                <span class="black">Валюта:</span>
                <label>
                    <input type="text" name="currency" value="{{$account->currency}}" class="input-default">
                </label>
            </div>
            <div class="form-block">
                <span class="black">Банк-корреспондент (на английском языке):</span>
                <label>
                    <input type="text" name="currency_cor_bank" value="{{$account->cor_bank}}" class="input-default">
                </label>
            </div>
            <div class="form-block">
                <span class="black">Страна нахождения банка-корреспондента:</span>
                <label>
                    <input type="text" name="currency_country_bank" value="{{$account->country_bank}}"
                           class="input-default">
                </label>
            </div>
            <div class="form-block">
                <span class="black">Город нахождения банка-корреспондента (на английском языке):</span>
                <label>
                    <input type="text" name="currency_city_bank" value="{{$account->city_bank}}" class="input-default">
                </label>
            </div>
            <div class="form-block">
                <span class="black">SWIFT банка-корреспондента:</span>
                <label>
                    <input type="text" name="currency_swift_bank" value="{{$account->swift_bank}}"
                           class="input-default">
                </label>
            </div>
            <div class="form-block">
                <span class="black">Номер счета в банке-корреспонденте:</span>
                <label>
                    <input type="text" name="currency_account_number_cor_bank"
                           value="{{$account->account_number_cor_bank}}" class="input-default">
                </label>
            </div>
            <div class="form-block">
                <span class="black">Наименование банка бенефициара (на английском языке):</span>
                <label>
                    <input type="text" name="currency_beneficiary_name_bank" value="{{$account->beneficiary_name_bank}}"
                           class="input-default">
                </label>
            </div>
            <div class="form-block">
                <span class="black">Страна нахождения Банка бенефициара:</span>
                <label>
                    <input type="text" name="currency_beneficiary_country_bank"
                           value="{{$account->beneficiary_country_bank}}" class="input-default">
                </label>
            </div>
            <div class="form-block">
                <span class="black">Город нахождения Банка бенефициара (на английском языке):</span>
                <label>
                    <input type="text" name="currency_beneficiary_city_bank" value="{{$account->beneficiary_city_bank}}"
                           class="input-default">
                </label>
            </div>
            <div class="form-block">
                <span class="black">Счет бенефициара платежа:</span>
                <label>
                    <input type="text" name="currency_account_beneficiary_bank"
                           value="{{$account->account_beneficiary_bank}}" class="input-default">
                </label>
            </div>
            <div class="form-block">
                <span class="black">ИНН банка</span>
                <label>
                    <input type="text" name="currency_tin_bank" value="{{$account->tin_bank}}" class="input-default">
                </label>
            </div>
            <div class="form-block">
                <span class="black">Корреспондентский счёт:</span>
                <label>
                    <input type="text" name="currency_cor_account_bank" value="{{$account->cor_account_bank}}"
                           class="input-default">
                </label>
            </div>
            <div class="form-block">
                <span class="black">Бенефициар платежа (на английском языке):</span>
                <label>
                    <input type="text" name="currency_pay_beneficiary" value="{{$account->pay_beneficiary}}"
                           class="input-default">
                </label>
            </div>
            <div class="form-block">
                <span class="black">Адрес бенефициара платежа (на английском языке):</span>
                <label>
                    <input type="text" name="currency_pay_address" value="{{$account->pay_address}}"
                           class="input-default">
                </label>
            </div>
            <div class="form-block center">
                <button class="btn-green">Сохранить</button>
            </div>
        </form>
    </div>
@endforeach
--!>
