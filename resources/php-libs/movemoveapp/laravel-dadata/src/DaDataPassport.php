<?php

namespace MoveMoveIo\DaData;

/**
 * Class DaDataPassport
 */
class DaDataPassport extends DaDataService
{
    /**
     * Standardization passports
     *
     * @throws \Exception
     */
    public function standardization(string $id): array
    {
        return $this->cleanerApi()->post('clean/passport', [$id]);
    }

    /**
     * Defin FMS unit by passport code or name
     *
     * @throws \Exception
     */
    public function fms(string $passport, int $count = 10): array
    {
        return $this->suggestApi()->post('rs/suggest/fms_unit', [
            'query' => $passport,
            'count' => $count,
        ]);
    }
}
