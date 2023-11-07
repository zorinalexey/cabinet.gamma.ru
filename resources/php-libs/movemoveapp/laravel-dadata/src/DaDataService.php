<?php

namespace MoveMoveIo\DaData;

use MoveMoveIo\DaData\Providers\CleanerDaDataProvider;
use MoveMoveIo\DaData\Providers\SuggestDaDataProvider;

/**
 * Class DaDataService
 */
class DaDataService
{
    /**
     * @var string|null
     */
    protected $token;

    /**
     * @var string|null
     */
    protected $secret;

    /**
     * @var int
     */
    protected $timeout;

    /**
     * @var CleanerDaDataProvider
     */
    protected $cleanerApi;

    /**
     * @var SuggestDaDataProvider
     */
    protected $suggestApi;

    /**
     * DaDataService constructor.
     */
    public function __construct()
    {
        if (file_exists(config_path().'/dadata.php')) {
            $this->token = config('dadata.token');
            $this->secret = config('dadata.secret');
            $this->timeout = config('dadata.timeout');
        } else {
            $this->token = env('DADATA_TOKEN', null);
            $this->secret = env('DADATA_SECRET', null);
            $this->timeout = env('DADATA_TIMEOUT', 10);
        }
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function getSecret(): ?string
    {
        return $this->secret;
    }

    public function getTimeout(): ?int
    {
        return $this->timeout;
    }

    public function cleanerApi(string $v = 'v1'): CleanerDaDataProvider
    {
        if (! $this->cleanerApi instanceof CleanerDaDataProvider) {
            $this->cleanerApi = new CleanerDaDataProvider($this->token, $this->secret, $this->timeout, $v);
        }

        return $this->cleanerApi;
    }

    public function suggestApi(string $v = '4_1'): SuggestDaDataProvider
    {
        if (! $this->suggestApi instanceof SuggestDaDataProvider) {
            $this->suggestApi = new SuggestDaDataProvider($this->token, $this->secret, $this->timeout, $v);
        }

        return $this->suggestApi;
    }
}
