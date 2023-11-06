<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserPassport;
use Ekapusta\OAuth2Esia\Provider\EsiaProvider;
use Ekapusta\OAuth2Esia\Security\JWTSigner\OpenSslCliJwtSigner;
use Ekapusta\OAuth2Esia\Security\Signer\OpensslCli;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

final class EsiaController extends Controller
{

    /**
     * @return RedirectResponse
     */
    public function auth(): RedirectResponse
    {
        $provider = self::getProvider();
        $authUrl = $provider->getAuthorizationUrl();
        $_SESSION['oauth2.esia.state'] = $provider->getState();
        return redirect()->intended($authUrl);
    }

    private static function getProvider(): EsiaProvider
    {
        $config = config('esia');
        return new EsiaProvider([
            'clientId' => $config['clientId'],
            'redirectUri' => $config['redirectUrl'],
            'defaultScopes' => $config['scope'],
        ], [
            'signer' => new OpensslCli($config['certPath'], $config['privateKeyPath'], $config['privateKeyPassword'], $config['toolPath']),
            'remoteSigner' => new OpenSslCliJwtSigner($config['toolPath']),
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws IdentityProviderException
     */
    public function login(Request $request): RedirectResponse
    {
        $authByPassport = false;
        $provider = self::getProvider();
        $state = $request['state'];
        $code = $request['code'];
        $session = $_SESSION['oauth2.esia.state'];
        if ($session !== $state) {
            abort(403);
        }
        $token = $provider->getAccessToken('authorization_code', ['code' => $code]);
        $esiaPersonData = $provider->getResourceOwner($token);
        $esiaUser = $esiaPersonData->toArray();
        if ($userByEsiaOID = User::where('esia_id', $esiaUser['resourceOwnerId'])->first()) {
            Auth::login($userByEsiaOID);
        }
        if ($esiaUserPassport = self::getUserPassport($esiaUser)) {
            $authByPassport = UserPassport::where('series', $esiaUserPassport['series'])
                ->where('number', $esiaUserPassport['number'])->first();
            if ($authByPassport) {
                Auth::login($authByPassport->user);
                $authByPassport->esia_id = $esiaUser['resourceOwnerId'];
                $authByPassport->save();
            }
        }
        if ($userByEsiaOID || $authByPassport) {
            return redirect()->intended(route('cabinet'));
        }
        return redirect()->intended('/');
    }

    private static function getUserPassport(array $esiaUser): array|null
    {
        foreach ($esiaUser['documents']['elements'] as $document) {
            if ($document['type'] == 'RF_PASSPORT' and $document['vrfStu'] == 'VERIFIED') {
                return $document;
            }
        }
        return null;
    }
}
