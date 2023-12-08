<?php
declare(strict_types=1);

namespace App\Transformers\Customers;

use App\Transformers\BaseTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class GetAllCustomersTransformer
 * @package App\Transformers\Customers
 * @author Nicodemus Rada <nincorada@gmail.com>
 * @since 2023.12.08
 */
class GetAllCustomersTransformer extends BaseTransformer
{
    /**
     * Transform response
     * @return JsonResponse
     */
    public function transform(): JsonResponse
    {
        return $this->respond($this->createCollection()->transformWith(function ($data) {
            return [
                'name'    => sprintf('%s %s', $data['firstName'], $data['lastName']),
                'email'   => $data['email'],
                'country' => $data['country'],
            ];
        }));
    }
}
