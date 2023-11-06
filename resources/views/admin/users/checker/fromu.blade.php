@if(!$check['fromu'])
    <span class="text-warning">Проверка не проводилась</span>
@elseif(!$check['fromu']->check)
    <span class="text-success">Проверка пройдена</span>
@else
    <span class="text-danger">{{$check['fromu']->check}}</span>
@endif

