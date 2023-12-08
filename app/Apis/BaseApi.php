<?php

namespace App\Apis;

use App\Traits\ApiRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;

/**
 * Class BaseApi
 * @package App\Enums
 * @author Nicodemus Rada <nincorada@gmail.com>
 * @since 2023.12.08
 */
class BaseApi
{
    use ApiRequest;

    /**
     * Perform API request
     * @param string  $method
     * @param string  $uri
     * @param mixed[] $request
     */
    protected function request(string $method, string $uri, array $request = []): array
    {
        $response = $this->httpRequest($method, $uri, $request);
        $this->logApiRequest($method, $request, $uri, $response);

        return $this->formatResponse($response);
    }
    /**
     * Logs API requests
     * @param string   $method
     * @param mixed[]  $request
     * @param string   $uri
     * @param Response $response
     */
    protected function logApiRequest(string $method, array $request, string $uri, Response $response): void
    {
        Log::info('Third Party API Request', [
            'headers'     => $response->headers(),
            'method'      => $method,
            'request'     => $request,
            'endpoint'    => $uri,
            'response'    => $response->json(),
            'status_code' => $response->status()
        ]);
    }
}