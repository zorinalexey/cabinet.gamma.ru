@if(!$check['passport'])
    <span class="text-warning">Проверка не проводилась</span>
@elseif(!$check['passport']->check)
    <span class="text-success">Паспорт действителен</span>
@else
    <span class="text-danger">Паспорт не действителен</span>
@endif
