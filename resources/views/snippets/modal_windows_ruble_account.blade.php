<div id="addRubles" class="popup-wrapper">
    <form class="modal-body" method="POST" action="{{route('add_ruble_account')}}">
        @csrf
        <input type="hidden" name="user_id" value="{{$user->id}}">
        <div class="form-block">
            <span class="black">Название банка:</span>
            <label>
                <input type="text" name="ru_bank_name" class="input-default">
            </label>
        </div>
        <div class="form-block">
            <span class="black">Страна банка:</span>
            <label>
                <input type="text" name="ru_bank_country" class="input-default">
            </label>
        </div>
        <div class="form-block">
            <span class="black">Город :</span>
            <label>
                <input type="text" name="ru_bank_city" class="input-default">
            </label>
        </div>
        <div class="form-block">
            <span class="black">БИК</span>
            <label>
                <input type="text" name="ru_bank_bic" class="input-default">
            </label>
        </div>
        <div class="form-block">
            <span class="black">ИНН банка</span>
            <label>
                <input type="text" name="ru_bank_inn" class="input-default">
            </label>
        </div>
        <div class="form-block">
            <span class="black">Корреспондентский счёт:</span>
            <label>
                <input type="text" name="ru_bank_cor_account" class="input-default">
            </label>
        </div>
        <div class="form-block">
            <span class="black">Расчетный счет:</span>
            <label>
                <input type="text" name="ru_bank_pay_account" class="input-default">
            </label>
        </div>
        <div class="form-block">
            <span class="black">Получатель платежа:</span>
            <label>
                <input type="text" name="ru_bank_recipient"
                       value="{{$user->lastname}} {{$user->name}} {{$user->patronymic}}" class="input-default">
            </label>
        </div>
        <div class="form-block center">
            <button class="btn-green">Добавить</button>
        </div>
    </form>
</div>

@foreach($user->ruble_accounts as $account)
    <div id="editRubles_{{$account->id}}" class="popup-wrapper">
        <form class="modal-body" method="POST" action="{{route('edit_ruble_account', $account->id)}}">
            @csrf
            <input type="hidden" name="user_id" value="{{$user->id}}">
            <div class="form-block">
                <span class="black">Название банка:</span>
                <label>
                    <input type="text" name="ru_bank_name" class="input-default" value="{{$account->bank_name}}">
                </label>
            </div>
            <div class="form-block">
                <span class="black">Страна банка:</span>
                <label>
                    <input type="text" name="ru_bank_country" class="input-default" value="{{$account->bank_country}}">
                </label>
            </div>
            <div class="form-block">
                <span class="black">Город :</span>
                <label>
                    <input type="text" name="ru_bank_city" class="input-default" value="{{$account->bank_city}}">
                </label>
            </div>
            <div class="form-block">
                <span class="black">БИК</span>
                <label>
                    <input type="text" name="ru_bank_bic" class="input-default" value="{{$account->bic}}">
                </label>
            </div>
            <div class="form-block">
                <span class="black">ИНН банка</span>
                <label>
                    <input type="text" name="ru_bank_inn" class="input-default" value="{{$account->tin}}">
                </label>
            </div>
            <div class="form-block">
                <span class="black">Корреспондентский счёт:</span>
                <label>
                    <input type="text" name="ru_bank_cor_account" class="input-default"
                           value="{{$account->cor_account}}">
                </label>
            </div>
            <div class="form-block">
                <span class="black">Расчетный счет:</span>
                <label>
                    <input type="text" name="ru_bank_pay_account" class="input-default"
                           value="{{$account->payment_account}}">
                </label>
            </div>
            <div class="form-block">
                <span class="black">Получатель платежа:</span>
                <label>
                    <input type="text" name="ru_bank_recipient" value="{{$account->payment_recipient}}"
                           class="input-default">
                </label>
            </div>
            <div class="form-block center">
                <button class="btn-green">Сохранить</button>
            </div>
        </form>
    </div>
@endforeach

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/suggestions-jquery@21.12.0/dist/css/suggestions.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/suggestions-jquery@21.12.0/dist/js/jquery.suggestions.min.js"></script>
<script>
    var dadata_token = "{{$dadata['token']}}";

    $('input[name="ru_bank_name"]').suggestions({
        token: dadata_token,
        type: "BANK",
        onSelect: function(suggestion) {
            setRuBankParams(suggestion);
        }
    })

    $('input[name="ru_bank_cor_account"]').suggestions({
        token: dadata_token,
        type: "BANK",
        onSelect: function(suggestion) {
            setRuBankParams(suggestion);
        }
    })

    $('input[name="ru_bank_inn"]').suggestions({
        token: dadata_token,
        type: "BANK",
        onSelect: function(suggestion) {
            setRuBankParams(suggestion);
        }
    })

    $('input[name="ru_bank_bic"]').suggestions({
        token: dadata_token,
        type: "BANK",
        onSelect: function(suggestion) {
            setRuBankParams(suggestion);
        }
    })

    function setRuBankParams(params){
        var data = params.data;
        if (!data){
            return;
        }
        $('input[name="ru_bank_name"]').val(data.name.payment);
        $('input[name="ru_bank_country"]').val(data.address.data.country);
        $('input[name="ru_bank_city"]').val(data.address.data.city_with_type);
        $('input[name="ru_bank_inn"]').val(data.inn);
        $('input[name="ru_bank_bic"]').val(data.bic);
        $('input[name="ru_bank_cor_account"]').val(data.correspondent_account);
    }

    $('input[name="ru_bank_recipient"]').suggestions({
        token: dadata_token,
        type: "NAME"
    });
</script>
