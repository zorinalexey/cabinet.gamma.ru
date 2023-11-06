<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\DocumentService;
use App\Http\Services\SmsService;
use App\Http\Services\SpecDep\GetDocumentService;
use App\Models\UserDocument;
use Illuminate\Contracts\Foundation\Application as App;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JsonException;

/**
 *
 */
final class FundController extends Controller
{
    /**
     * @param Request $request
     * @return Application|Response|App|ResponseFactory
     */
    public function getDocuments(Request $request): Application|Response|App|ResponseFactory
    {
        ini_set('memory_limit', -1);
        $data = [
            'ok' => true,
            'error' => true,
        ];
        $account = (object)$request[0];
        $user = (object)$request[1];
        $fund = (object)$request[2];
        $blank = GetDocumentService::blank($user, $account, $fund);
        $appPurchase = GetDocumentService::appPurchase($user, $account, $fund);
        $appOpenPersonalAccount = GetDocumentService::requestOpenAccount($user, $account, $fund);
        if ($appPurchase && $blank && $appOpenPersonalAccount) {
            $data['error'] = false;
            $data['body'] = [
                'bank_account' => $account,
                'user' => $user,
                'fund' => $fund,
                'blank' => UserDocument::where('path', $blank)->first(),
                'purchaseRequest' => UserDocument::where('path', $appPurchase)->first(),
                'appOpenPersonalAccount' => UserDocument::where('path', $appOpenPersonalAccount)->first(),
            ];
        }
        return response($data);
    }

    /**
     * @param Request $request
     * @return Application|Response|App|ResponseFactory
     * @throws JsonException
     */
    public function sendCodeSignDocuments(Request $request): Application|Response|App|ResponseFactory
    {
        $phone = ((object)$request[0]['user'])->phone;
        $documents[] = (object)$request[0]['blank'];
        $documents[] = (object)$request[0]['purchaseRequest'];
        $documents[] = (object)$request[0]['appOpenPersonalAccount'];
        $code = random_int(100000, 999999);
        $message = "Для подписания пакета документов введите код : " . $code;
        foreach ($documents as $document) {
            if (!$document->sign_status) {
                $userDocument = UserDocument::find($document->id);
                $userDocument->sign_code = $code;
                $userDocument->save();
            }
        }
        SmsService::send($phone, $message);
        return response(['ok' => true]);
    }

    /**
     * @param Request $request
     * @return Application|Response|App|ResponseFactory
     */
    public function signDocuments(Request $request): Application|Response|App|ResponseFactory
    {
        $data = [
            'ok' => true,
            'error' => 'Не верно введен код для подписания документов'
        ];
        $code = (string)$request[1];
        $user = (object)$request[0]['user'];
        $documents = UserDocument::where('sign_code', $code)->where('user_id', $user->id)->get();
        foreach ($documents as $document) {
            if ($document->sign_code === $code) {
                $document->sign_status = true;
                $document->save();
                DocumentService::createSignDocument($document, (string)$code);
                $data['error'] = false;
            }
        }
        return response($data);
    }
}
