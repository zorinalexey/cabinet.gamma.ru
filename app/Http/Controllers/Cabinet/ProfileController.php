<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Http\Services\ProfileService;
use App\Models\UserCurrencyAccount;
use App\Models\UserRubleAccount;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application as App;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class ProfileController extends Controller
{
    /**
     * Страница профиля авторизованного пользователя
     */
    public function profile(): Application|Factory|View|App|RedirectResponse
    {
        $title = 'Профиль инвестора <<'.Auth::user()->lastname.' '.Auth::user()->name.'>>';
        $dadata = config('dadata');

        return view('front.profile', compact('title', 'dadata'));
    }

    /**
     * Добавить рублевый счет
     */
    public function addRubleAccount(Request $request): RedirectResponse
    {
        $data = ProfileService::getRubleAccountData($request);
        $account = new UserRubleAccount();
        $account->user_id = $data['user_id'];
        $account->bank_name = $data['ru_bank_name'];
        $account->bank_country = $data['ru_bank_country'];
        $account->bank_city = $data['ru_bank_city'];
        $account->bic = $data['ru_bank_bic'];
        $account->tin = $data['ru_bank_inn'];
        $account->cor_account = $data['ru_bank_cor_account'];
        $account->payment_account = $data['ru_bank_pay_account'];
        $account->payment_recipient = $data['ru_bank_recipient'];
        $account->save();

        return redirect(route('profile'));
    }

    /**
     * Редактировать рублевый счет
     */
    public function editRubleAccount(int $id, Request $request): RedirectResponse
    {
        $data = ProfileService::getRubleAccountData($request);
        $account = UserRubleAccount::find($id);
        $account->bank_name = $data['ru_bank_name'];
        $account->bank_country = $data['ru_bank_country'];
        $account->bank_city = $data['ru_bank_city'];
        $account->bic = $data['ru_bank_bic'];
        $account->tin = $data['ru_bank_inn'];
        $account->cor_account = $data['ru_bank_cor_account'];
        $account->payment_account = $data['ru_bank_pay_account'];
        $account->payment_recipient = $data['ru_bank_recipient'];
        $account->save();

        return redirect(route('profile'));
    }

    /**
     * Удалить рублевый счет
     */
    public function dropRubleAccount(int $id): RedirectResponse
    {
        UserRubleAccount::find($id)->delete();

        return redirect(route('profile'));
    }

    /**
     * Добавить валютный счет
     */
    public function addCurrencyAccount(Request $request): RedirectResponse
    {
        $data = ProfileService::getCurrencyAccountData($request);
        $account = new UserCurrencyAccount();
        $account->user_id = $data['user_id'];
        $account->currency = $data['currency'];
        $account->cor_bank = $data['currency_cor_bank'];
        $account->country_bank = $data['currency_country_bank'];
        $account->city_bank = $data['currency_city_bank'];
        $account->swift_bank = $data['currency_swift_bank'];
        $account->account_number_cor_bank = $data['currency_account_number_cor_bank'];
        $account->beneficiary_name_bank = $data['currency_beneficiary_name_bank'];
        $account->beneficiary_city_bank = $data['currency_beneficiary_city_bank'];
        $account->beneficiary_country_bank = $data['currency_beneficiary_country_bank'];
        $account->account_beneficiary_bank = $data['currency_account_beneficiary_bank'];
        $account->tin_bank = $data['currency_tin_bank'];
        $account->cor_account_bank = $data['currency_cor_account_bank'];
        $account->pay_beneficiary = $data['currency_pay_beneficiary'];
        $account->pay_address = $data['currency_pay_address'];
        $account->save();

        return redirect(route('profile'));
    }

    /**
     * Редактировать валютный счет
     */
    public function editCurrencyAccount(int $id, Request $request): RedirectResponse
    {
        $data = ProfileService::getCurrencyAccountData($request);
        $account = UserCurrencyAccount::find($id);
        $account->currency = $data['currency'];
        $account->cor_bank = $data['currency_cor_bank'];
        $account->country_bank = $data['currency_country_bank'];
        $account->city_bank = $data['currency_city_bank'];
        $account->swift_bank = $data['currency_swift_bank'];
        $account->account_number_cor_bank = $data['currency_account_number_cor_bank'];
        $account->beneficiary_name_bank = $data['currency_beneficiary_name_bank'];
        $account->beneficiary_city_bank = $data['currency_beneficiary_city_bank'];
        $account->account_beneficiary_bank = $data['currency_account_beneficiary_bank'];
        $account->tin_bank = $data['currency_tin_bank'];
        $account->cor_account_bank = $data['currency_cor_account_bank'];
        $account->pay_beneficiary = $data['currency_pay_beneficiary'];
        $account->pay_address = $data['currency_pay_address'];
        $account->save();

        return redirect(route('profile'));
    }

    /**
     * Удалить валютный счет
     */
    public function dropCurrencyAccount(int $id): RedirectResponse
    {
        UserCurrencyAccount::find($id)->delete();

        return redirect(route('profile'));
    }

    /**
     * Редактировать номер телефона инвестора
     */
    public function editPhone(Request $request): RedirectResponse
    {
        $data = ProfileService::getDataEditPhone($request);
        $user = Auth::user();
        $user->phone = $data['phone'];
        $user->save();

        return redirect(route('profile'));
    }

    /**
     * Редактировать адрес электронной почты инвестора
     */
    public function editEmail(Request $request): RedirectResponse
    {
        $data = ProfileService::getDataEditEmail($request);
        $user = Auth::user();
        $user->email = $data['email'];
        $user->save();

        return redirect(route('profile'));
    }
}
