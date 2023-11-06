@if(!$check['p639'])
    <span class="text-warning">Проверка не проводилась</span>
@elseif(!$check['p639']->check)
    <span class="text-success">Проверка пройдена</span>
@else
    <span class="text-danger">{{$check['p639']->check}}</span>
@endif
