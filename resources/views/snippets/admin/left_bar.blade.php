<aside class="left-sidebar">
    <div class="scroll-sidebar">
        <div class="user-profile">
            <div class="user-pro-body">
                <div class="dropdown">
                    <a href="javascript:void(0)" class="dropdown-toggle u-dropdown link hide-menu"
                       data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="false">{{$admin->lastname}} {{$admin->name}} <span class="caret"></span></a>
                    <div class="dropdown-menu animated flipInY">
                        <a href="{{route('profile')}}" target="_blank" class="dropdown-item"><i class="ti-user"></i> Мой
                            профиль</a>
                        <div class="dropdown-divider"></div>
                        <a href="{{route('admin.my_profile_settings')}}" class="dropdown-item"><i
                                class="ti-settings"></i> Настройки аккаунта</a>
                        <div class="dropdown-divider"></div>
                        <a href="{{route('logout')}}" class="dropdown-item"><i class="fas fa-power-off"></i> Выход</a>
                    </div>
                </div>
            </div>
        </div>
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li><a href="{{route('admin.user.list')}}">Клиенты</a></li>
                <li><a href="{{route('admin.fund.list')}}">Фонды</a></li>
                <li><a href="{{route('admin.document.list')}}">Документы</a></li>
                <li><a href="{{route('admin.infinitum.list')}}">Документы Спец.Рега</a></li>
                <li><a href="{{route('admin.page.list')}}">Страницы</a></li>
                <li><a href="{{route('admin.post.list')}}">Новости</a></li>
                <li><a href="{{route('admin.omitted.list')}}">Инвест. комитет</a></li>
                <li><a href="{{route('admin.tech.list')}}">Технический раздел</a></li>
            </ul>
        </nav>
    </div>
</aside>
