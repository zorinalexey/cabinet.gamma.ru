@if(!$check['mvk'])
    <span class="text-warning">Проверка не проводилась</span>
@elseif(!$check['mvk']->check)
    <span class="text-success">Проверка пройдена</span>
@else
    Решение - <span class="text-danger">{{$check['mvk']->check}}</span>
@endif
