<?php

namespace App\Http\Services\SpecDep;

use App\Http\Services\DocumentService;
use App\Models\Fund;
use App\Models\User;
use App\Models\UserDocument;
use App\Models\UserRubleAccount;
use stdClass;

final class GetDocumentService
{
    public static function blank(stdClass $user_account, stdClass $bank_account, stdClass $fund_account): ?string
    {
        $document_name = 'АНКЕТА ЗАРЕГИСТРИРОВАННОГО ФИЗИЧЕСКОГО ЛИЦА В РЕЕСТРЕ ВЛАДЕЛЬЦЕВ ИНВЕСТИЦИОННЫХ ПАЕВ';
        $user = User::find($user_account->id);
        $fund = Fund::find($fund_account->id);
        $account = UserRubleAccount::find($bank_account->id);
        $document_hash = $user->id.'_anketa_account_'.$account->id.'_fund_'.$fund->id;
        $document = UserDocument::query()->where('search_hash', $document_hash)->first();

        if ($user && $document && $link = self::getLink($user, $account, $fund, $document, $document_name)) {
            return $link;
        }

        return Documents::createBlank($user, $account, $fund);
    }

    private static function getLink(User $user, UserRubleAccount $account, Fund $fund, UserDocument|null $document, string $document_name): ?string
    {
        $link = DocumentService::getLink($user, $account, $fund, $document, $document_name);
        if ($link) {
            return $link;
        }

        return null;
    }

    public static function appPurchase(stdClass $user_account, stdClass $bank_account, stdClass $fund_account): ?string
    {
        $document_name = mb_strtoupper('Заявка на приобретение инвестиционных паев');
        $user = User::find($user_account->id);
        $fund = Fund::find($fund_account->id);
        $account = UserRubleAccount::find($bank_account->id);
        $document_hash = $user->id.'_app_account_'.$account->id.'_fund_'.$fund->id;
        $document = UserDocument::where('search_hash', $document_hash)->first();
        if ($user && $document && $link = self::getLink($user, $account, $fund, $document, $document_name)) {
            return $link;
        }

        return Documents::appPurchase($user, $account, $fund);
    }

    public static function requestOpenAccount(stdClass $user_account, stdClass $bank_account, stdClass $fund_account): ?string
    {
        $document_name = mb_strtoupper('ЗАЯВЛЕНИЕ ОБ ОТКРЫТИИ ЛИЦЕВОГО СЧЕТА ЗАРЕГИСТРИРОВАННОГО ЛИЦА');
        $user = User::find($user_account->id);
        $fund = Fund::find($fund_account->id);
        $account = UserRubleAccount::find($bank_account->id);
        $document_hash = $user->id.'_request_account_'.$account->id.'_fund_'.$fund->id;
        $document = UserDocument::where('search_hash', $document_hash)->first();

        if ($user && $document && $link = self::getLink($user, $account, $fund, $document, $document_name)) {
            return $link;
        }

        return Documents::requestOpenAccount($user, $account, $fund);
    }
}
