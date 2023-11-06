<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Http\Services\SmsService;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use JsonException;

final class LoginController extends Controller
{
    /**
     * @return Application|Factory|View|RedirectResponse
     *
     * @throws JsonException
     */
    public function getCode(Request $request)
    {
        $data = $request->validate([
            'phone' => ['required'],
            '_token' => ['required'],
        ]);
        $vars = [
            'title' => 'Авторизация в личном кабинете инвестора',
            'message' => 'Не верно указан номер телефона',
            'button' => false,
        ];
        $user = User::where('phone', $data['phone'])->first();
        if ($user) {
            $sendSms = SmsService::sendAutCode($user);
            $vars['phone'] = (string) $user->phone;
            $vars['button'] = 'Войти';
            if (! $sendSms['errors']) {
                $user->save();
                $vars['message'] = 'Введите код из СМС';
            } else {
                $vars['message'] = (string) $sendSms['message'];
            }
        }

        return view('front.loginCode', $vars);
    }

    public function auth(Request $request): RedirectResponse
    {
        if (Auth::user()) {
            return redirect(route('cabinet'));
        }
        $data = $request->validate([
            'phone' => ['required'],
            '_token' => ['required'],
            'code' => ['required'],
        ]);

        if ($user = User::where('phone', $data['phone'])->where('code', $data['code'])->first()) {
            Auth::login($user);
            $request->session()->regenerate();

            return redirect(route('cabinet'));
        }

        return redirect()->back();
    }

    public function login(): Application|Factory|View|RedirectResponse
    {
        if (! Auth::user()) {
            return view('front.login', ['title' => 'Авторизация в личном кабинете инвестора']);
        }

        return redirect(route('cabinet'));
    }

    public function logOut(): RedirectResponse
    {
        Auth::logout();

        return redirect()->intended('/');
    }
}
