<?php

namespace MoveMoveIo\DaData\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class DaDataEmail
 *
 * @method \MoveMoveIo\DaData\DaDataEmail standardization(string $email)
 * @method \MoveMoveIo\DaData\DaDataEmail prompt(string $email, int $count)
 */
class DaDataEmail extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'da_data_email';
    }
}
