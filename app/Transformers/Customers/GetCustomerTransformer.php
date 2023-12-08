<?php
declare(strict_types=1);

namespace App\Transformers\Customers;

use App\Transformers\BaseTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class GetCustomerTransformer
 * @package App\Transformers\Customers
 * @author Nicodemus Rada <nincorada@gmail.com>
 * @since 2023.12.08
 */
class GetCustomerTransformer extends BaseTransformer
{
    /**
     * Transform response
     * @return JsonResponse
     */
    public function transform(): JsonResponse
    {
        return $this->respond($this->createItem()->transformWith(function ($data) {
            return [
                'data' => [
                    'name'     => sprintf('%s %s', $data['firstName'], $data['lastName']),
                    'email'    => $data['email'],
                    'username' => $data['userName'],
                    'gender'   => $data['gender'],
                    'country'  => $data['country'],
                    'city'     => $data['city'],
                    'phone'    => $data['phone'],
                ]
            ];
        }));
    }
}
