<?php

return [
    'login' => env('SMSC_RU_LOGIN', false),
    'password' => env('SMSC_RU_PWD', false),
    'sender' => env('SMSC_RU_SENDER_NAME', false),
    'test' => env('SMS_TEST', false),
    'debug' => env('SMS_DEBUG', false),
];
