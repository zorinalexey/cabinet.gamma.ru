@if(!$check['passport'])
    <span class="text-warning" title="Проверка не проводилась"><i class="mdi mdi-clock-alert"></i></span>
@elseif(!$check['passport']->check)
    <span class="text-success" title="Паспорт действителен"><i
            class="mdi mdi-checkbox-marked-circle-outline"></i></span>
@else
    <span class="text-danger" title="Паспорт не действителен"><i class="mdi mdi-alert-circle-outline"></i></span>
@endif

@if(!$check['fromu'])
    <span class="text-warning" title="Проверка не проводилась"><i class="mdi mdi-clock-alert"></i></span>
@elseif(!$check['fromu']->check)
    <span class="text-success" title="Проверка по спискам ФРОМУ пройдена"><i
            class="mdi mdi-checkbox-marked-circle-outline"></i></span>
@else
    <span class="text-danger" title="Проверка по спискам ФРОМУ не пройдена - {{$check['fromu']->check}}"><i
            class="mdi mdi-alert-circle-outline"></i></span>
@endif

@if(!$check['fedsfm'])
    <span class="text-warning" title="Проверка не проводилась"><i class="mdi mdi-clock-alert"></i></span>
@elseif(!$check['fedsfm']->check)
    <span class="text-success" title="Проверка по спискам РосФинМониторинга пройдена"><i
            class="mdi mdi-checkbox-marked-circle-outline"></i></span>
@else
    <span class="text-danger" title="Проверка по спискам РосФинМониторинга не пройдена - {{$check['fedsfm']->check}}"><i
            class="mdi mdi-alert-circle-outline"></i></span>
@endif

@if(!$check['mvk'])
    <span class="text-warning" title="Проверка не проводилась"><i class="mdi mdi-clock-alert"></i></span>
@elseif(!$check['mvk']->check)
    <span class="text-success" title="Проверка по спискам МВК пройдена"><i
            class="mdi mdi-checkbox-marked-circle-outline"></i></span>
@else
    <span class="text-danger" title="Проверка по спискам МВК не пройдена - {{$check['mvk']->check}}"><i
            class="mdi mdi-alert-circle-outline"></i></span>
@endif

@if(!$check['p639'])
    <span class="text-warning" title="Проверка не проводилась"><i class="mdi mdi-clock-alert"></i></span>
@elseif(!$check['p639']->check)
    <span class="text-success" title="Проверка по списки П-639 пройдена"><i
            class="mdi mdi-checkbox-marked-circle-outline"></i></span>
@else
    <span class="text-danger" title="Проверка по спискам П-639 не пройдена"><i class="mdi mdi-alert-circle-outline"></i></span>
@endif

