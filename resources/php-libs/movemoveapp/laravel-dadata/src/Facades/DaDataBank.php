<?php

namespace MoveMoveIo\DaData\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class DaDataBank
 *
 * @method \MoveMoveIo\DaData\DaDataBank id(string $bank)
 * @method \MoveMoveIo\DaData\DaDataBank prompt(string $company, int $coun, array $status, array $type, string $locations, string $locations_boost)
 */
class DaDataBank extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'da_data_bank';
    }
}
