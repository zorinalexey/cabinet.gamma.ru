@if(!$check['fedsfm'])
    <span class="text-warning">Проверка не проводилась</span>
@elseif(!$check['fedsfm']->check)
    <span class="text-success">Проверка пройдена</span>
@else
    <span class="text-danger">{{$check['fedsfm']->check}}</span>
@endif
