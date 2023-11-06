<?php

namespace App\Http\Services;

use App\Models\User;
use Kily\Payment\QR\Gost;

class QrCodePayment
{
    public static function getQrCode(User $user)
    {
        $company_info = config('company_details');
        $qrCode = new Gost();
        $qrCode->Name = $company_info['company_name'];
        $qrCode->PersonalAcc = $company_info['pya_account'];
        $qrCode->BankName = $company_info['bank_name'];
        $qrCode->BIC = $company_info['bic'];
        $qrCode->CorrespAcc = $company_info['cor_account'];
        $qrCode->PayeeINN = $company_info['inn'];
        $qrCode->KPP = $company_info['kpp'];
        $qrCode->Purpose = 'Оплата паев ОПИФ рыночных финансовых инструментов АРОМАТ Наши акции, '.
            $user->lastname.' '.
            $user->name.' '.
            $user->patronymic.
            ', заявка № ____ от ___г.';
        $qrCode->LastName = $user->lastname;
        $qrCode->FirstName = $user->name;
        $qrCode->MiddleName = $user->patronymic;
        if ($user->inn) {
            $qrCode->PayerINN = $user->inn->number;
        }

        return $qrCode;
    }
}
