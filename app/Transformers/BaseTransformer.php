<?php

namespace App\Transformers;

use App\Transformers\Serializer\ResponseSerializer;
use Faker\Provider\Uuid;
use Illuminate\Http\JsonResponse;
use Spatie\Fractal\Fractal;

/**
 * Class BaseTransformer
 * @package App\Transformers
 * @author Nicodemus Rada <nincorada@gmail.com>
 * @since 2023.12.08
 */
class BaseTransformer
{
    /**
     * Contains the response to be transformed.
     * @var array<int|string, mixed>
     */
    protected array $response = [];

    /**
     * Set response to be transformed.
     * @param array<int|string, mixed> $response
     * @return $this
     */
    public function response(array $response): self
    {
        $this->response = $response;
        return $this;
    }

    /**
     * Return error response.
     * @param array{int, string, array<string, mixed>} $errorResponse
     * @return JsonResponse
     */
    public function errorResponse(array $errorResponse): JsonResponse
    {
        return $this->respond($this->createItem($errorResponse)
            ->transformWith(function ($data) {
                return [
                    'error' => [
                        'status'  => $data[0],
                        'message' => $data[1]
                    ]
                ];
            }),
            $errorResponse[0], // Status code
        );
    }

    /**
     * Create Item datatype with the custom serializer.
     * This can be used for API with custom data.
     * @param array{0: int, 1: string, 2: array<string, mixed>}|null $response
     * @return Fractal
     */
    protected function createItem(?array $response = null): Fractal
    {
        if (empty($response) === true) {
            $response = $this->response;
        }

        return fractal()
            ->item($response)
            ->serializeWith(new ResponseSerializer());
    }

    /**
     * Create Collection datatype with the custom serializer.
     * This can be used for multidimensional arrays, enclosed with "data" key.
     * @return Fractal
     */
    protected function createCollection(): Fractal
    {
        return fractal()
            ->collection($this->response)
            ->serializeWith(new ResponseSerializer());
    }

    /**
     * Return response with headers.
     * @param Fractal $oFractal
     * @param int     $iStatusCode
     * @param array<string, mixed>   $aHeaders
     * @return JsonResponse
     */
    protected function respond(Fractal $oFractal, int $iStatusCode = 200, array $aHeaders = []): JsonResponse
    {
        return $oFractal->respond($iStatusCode, array_merge($aHeaders, [
            'trace_id' => $this->getTraceId()
        ]));
    }

    /**
     * Get trace id.
     * @return string
     */
    private function getTraceId(): string
    {
        return Uuid::uuid();
    }
}
