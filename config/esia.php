<?php

use Esia\Signer\CliSignerPKCS7;

return [
    'test' => env('ESIA_TEST', true),
    'clientId' => env('ESIA_CLIENT_ID'),
    'redirectUrl' => env('ESIA_LOGIN_URL'),
    'scope' => [
        'id_doc',
        'openid',
        'fullname'
    ],
    'signer' => CliSignerPKCS7::class,
    'certPath' => env('ESIA_CERT_PATH'),
    'privateKeyPath' => env('ESIA_PRIVATE_KEY_PATH'),
    'privateKeyPassword' => env('ESIA_PRIVATE_KEY_PASSWORD', null),
    'tmpPath' => '/var/tmp',
    'toolPath'=>'/usr/bin/openssl'
];
