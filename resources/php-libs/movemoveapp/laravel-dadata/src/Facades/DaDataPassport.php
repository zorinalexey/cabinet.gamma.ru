<?php

namespace MoveMoveIo\DaData\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class DaDataPassport
 *
 * @method \MoveMoveIo\DaData\DaDataPassport standardization(string $id)
 * @method \MoveMoveIo\DaData\DaDataPassport fms(string $passport, int $count)
 */
class DaDataPassport extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'da_data_passport';
    }
}
