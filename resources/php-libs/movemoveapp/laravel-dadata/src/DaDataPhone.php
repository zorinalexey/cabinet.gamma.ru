<?php

namespace MoveMoveIo\DaData;

/**
 * Class DaDataPhone
 */
class DaDataPhone extends DaDataService
{
    /**
     * Standardization phone
     *
     * Checks the phone according to the Rossvyaz directory, determines the operator,
     * taking into account the portability of numbers, fills in the country, city and time zone.
     *
     * @throws \Exception
     */
    public function standardization(string $phone): array
    {
        return $this->cleanerApi()->post('clean/phone', [$phone]);
    }
}
