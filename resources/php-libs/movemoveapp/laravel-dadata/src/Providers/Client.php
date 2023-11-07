<?php

namespace MoveMoveIo\DaData\Providers;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response as ResponseCode;

/**
 * Trait Client
 */
trait Client
{
    /**
     * @throws \Exception
     */
    public function postData(array $headers, string $url, array $data, int $timeout): array
    {
        $response = Http::withHeaders($headers)->timeout($timeout)->post($url, $data);

        return $this->data($response);
    }

    /**
     * @throws \Exception
     */
    protected function data(Response $response): array
    {
        $data = json_decode($response->body(), true);

        if ($response->status() != ResponseCode::HTTP_OK) {
            throw new \Exception($data['message'] ?? ResponseCode::$statusTexts[$response->status()], $response->status());
        }

        return $data;
    }

    /**
     * @throws \Exception
     */
    public function getData(array $headers, string $url, array $data, int $timeout): array
    {
        $response = Http::withHeaders($headers)->timeout($timeout)->get($url, $data);

        return $this->data($response);
    }
}
