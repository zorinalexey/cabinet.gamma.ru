<?php

namespace App\Http\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Kily\Payment\QR\Gost;

/**
 *
 */
final class ProfileService
{

    /**
     * @param Request $request
     * @return array
     */
    public static function getRubleAccountData(Request $request): array
    {
        return $request->validate([
            'ru_bank_name' => ['required'],
            'user_id' => ['required'],
            'ru_bank_country' => ['required'],
            'ru_bank_bic' => ['required'],
            'ru_bank_inn' => ['required'],
            'ru_bank_cor_account' => ['required'],
            'ru_bank_pay_account' => ['required'],
            'ru_bank_recipient' => ['required'],
            'ru_bank_city' => ['required'],
            '_token' => ['required'],
        ]);
    }

    /**
     * @param Request $request
     * @return array
     */
    public static function getCurrencyAccountData(Request $request)
    {
        return $request->validate([
            'user_id' => ['required'],
            'currency' => ['required'],
            'currency_beneficiary_country_bank' => ['required'],
            'currency_cor_bank' => ['required'],
            'currency_country_bank' => ['required'],
            'currency_city_bank' => ['required'],
            'currency_swift_bank' => ['required'],
            'currency_account_number_cor_bank' => ['required'],
            'currency_beneficiary_name_bank' => ['required'],
            'currency_beneficiary_city_bank' => ['required'],
            'currency_account_beneficiary_bank' => ['required'],
            'currency_tin_bank' => ['required'],
            'currency_cor_account_bank' => ['required'],
            'currency_pay_beneficiary' => ['required'],
            'currency_pay_address' => ['required'],
            '_token' => ['required'],
        ]);
    }

    /**
     * @param Request $request
     * @return array
     */
    public static function getDataEditPhone(Request $request): array
    {
        return $request->validate([
            'phone' => ['required'],
            '_token' => ['required'],
        ]);
    }

    /**
     * @param Request $request
     * @return array
     */
    public static function getDataEditEmail(Request $request): array
    {
        return $request->validate([
            'email' => ['required'],
            '_token' => ['required'],
        ]);
    }

    /**
     * @param array $company_details
     * @return Gost
     */
    public static function getQrCode(array $company_details): Gost
    {
        $user = Auth::user();
        $qrCode = new Gost();
        $inn = null;
        if ($user->inn) {
            $inn = $user->inn->number;
        }
        $qrCode->Name = $company_details['company_full_name'];
        $qrCode->PersonalAcc = $company_details['pya_account'];
        $qrCode->BankName = $company_details['bank_name'];
        $qrCode->BIC = $company_details['bic'];
        $qrCode->CorrespAcc = $company_details['cor_account'];
        $qrCode->PayeeINN = $company_details['inn'];
        $qrCode->KPP = $company_details['kpp'];
        $qrCode->Purpose = 'Оплата паев ОПИФ рыночных финансовых инструментов АРОМАТ Наши акции, ' .
            $user->lastname . ' ' .
            $user->name . ' ' .
            $user->patronymic .
            ', заявка № ____ от 20__г.';
        $qrCode->LastName = $user->lastname;
        $qrCode->FirstName = $user->name;
        $qrCode->MiddleName = $user->patronymic;
        $qrCode->PayerINN = $inn;
        return $qrCode;
    }
}
