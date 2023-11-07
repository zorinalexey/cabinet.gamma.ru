<?php

namespace MoveMoveIo\DaData\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class DaDataPhone
 *
 * @method \MoveMoveIo\DaData\DaDataPhone standardization(string $phone)
 */
class DaDataPhone extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'da_data_phone';
    }
}
