<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\SmsService;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application as App;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use JsonException;

final class LoginController extends Controller
{
    public function auth(Request $request): Application|Redirector|App|RedirectResponse
    {
        $data = $request->validate([
            'phone' => ['required'],
            'password' => ['required'],
            '_token' => ['required'],
        ]);
        $user = User::where('phone', $data['phone'])->where('code', $data['password'])->first();
        if ($user) {
            Auth::login($user);
            $request->session()->regenerate();

            return redirect(route('admin_main'));
        }

        return abort(403);
    }

    public function login(): Factory|View|Application|App
    {
        return view('admin.login');
    }

    /**
     * @throws JsonException
     */
    public function setNewPassword(Request $request): Application|Response|App|ResponseFactory
    {
        $phone = $request['phone'];
        $response = [
            'ok' => true,
            'error' => 'Администратор с указанным вами номером телефона не найден. '.
                'Или Вам уже был отправлен новый пароль в СМС. Повторите попытку через 5 минут',
        ];
        $user = User::where('phone', $phone)->where('role', 2, '>')->first();
        if ($user && strtotime((string) $user->updated_at) < time() - 60 * 5) {
            $code = random_int(10000, 999999);
            $message = 'Новый пароль для входа в панель администратора : '.$code;
            $user->code = $code;
            $user->save();
            SmsService::send($user->phone, $message);
            $response['error'] = false;
            $response['message'] = 'Новый пароль успешно установлен и отправлен Вам в СМС';
        }

        return response($response);
    }
}
