<?php

namespace App\Traits;

use Illuminate\Http\Client\Response;
use App\Constants\ApiConstants;
use Illuminate\Support\Facades\Http;

/**
 * Trait ApiRequest
 * @package App\Traits
 * @author Nicodemus Rada <nincorada@gmail.com>
 * @since 2023.12.08
 */
trait ApiRequest
{
    /**
     * Base URI.
     * @var string
     */
    private string $baseUri;

    /**
     * Sets base uri
     * @param string $baseUri
     */
    protected function setBaseUri(string $baseUri): void
    {
        $this->baseUri = $baseUri;
    }

    /**
     * Perform HTTP API request
     * @param string  $method
     * @param string  $uri
     * @param mixed[] $request
     */
    protected function httpRequest(string $method, string $uri, array $request = []): Response
    {
        $method = strtolower($method);
        return Http::{ $method }($this->baseUri . $uri, $request);
    }
    
    /**
     * Get result depending on the response and result params.
     * @param Response $response
     */
    protected function formatResponse(Response $response): array
    {
        $data = $response->json();
        $code = $response->getStatusCode();
        $success = $response->successful();

        return [
            ApiConstants::SUCCESS => $success,
            ApiConstants::CODE    => $code,
            ApiConstants::DATA    => $success ? ($data[ApiConstants::RESULTS] ?? $data) : $data
        ];
    }
}
