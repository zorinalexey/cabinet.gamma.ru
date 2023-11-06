<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use JsonException;
use stdClass;

final class SmsService
{
    /**
     * Отправить код для авторизации пользователя
     *
     * @throws JsonException
     */
    public static function sendAutCode(?User $user): array
    {
        $config = config('app');
        $result = [
            'errors' => true,
            'message' => 'Вам уже отправлен код. Повторите попытку позже или дождитесь СМС',
        ];
        if ($user && strtotime((string) $user->updated_at) < time() - 60 * 5) {
            $code = random_int(1000, 9999);
            $user->code = $code;
            $message = 'Ваш код для входа на сайте '.$config['url'].' : '.$code;
            self::send($user->phone, $message);
            $result['errors'] = false;
            $result['message'] = false;
        }

        return $result;
    }

    /**
     * Отправить сообщение
     *
     * @param  string  $phone Номер абонента
     * @param  string  $message Текст сообщения
     * @return stdClass|null
     *
     * @throws JsonException
     */
    public static function send(string $phone, string $message)
    {
        $config = config('smsc');
        $params = [
            'login' => $config['login'],
            'psw' => $config['password'],
            'phones' => preg_replace('~(\D)~u', '', $phone),
            'mes' => $message,
            'id' => time().random_int(10000, 99999),
            'sender' => $config['sender'],
            'cost' => 3,
            'fmt' => 3,
        ];
        if ($config['test']) {
            $params['test'] = 1;
        }
        $url = 'https://smsc.ru/sys/send.php?'.http_build_query($params);
        $stream_context_params = [
            'http' => [
                'method' => 'GET',
                'user_agent' => 'php-download master guzzle-library v0.1',
                'timeout' => 60 * 60,
                'max_redirects' => 0,
                'ignore_errors' => true,
                'request_fulluri' => true,
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ];
        $result = null;
        $response = file_get_contents($url, false, stream_context_create($stream_context_params));
        if ($response) {
            $result = json_decode($response, false, 512, JSON_THROW_ON_ERROR);
            $result->text = $message;
            $result->phone = $phone;
        }
        if ($config['test'] || $config['debug']) {
            Log::debug('smsc', compact('url', 'response', 'result', 'config', 'params'));
        }

        return $result;
    }
}
