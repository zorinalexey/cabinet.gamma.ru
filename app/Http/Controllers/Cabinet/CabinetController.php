<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application as App;
use Illuminate\Http\RedirectResponse;

final class CabinetController extends Controller
{
    /**
     * Личный кабинет инвестора главная страница авторизованного пользователя
     */
    public function cabinet(): Application|Factory|View|App|RedirectResponse
    {
        return view('front.cabinet');
    }

    public function returnMoney()
    {
    }
}
