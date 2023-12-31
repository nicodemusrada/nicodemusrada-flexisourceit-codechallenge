<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\CustomerRepository;
use App\Transformers\Customers\GetAllCustomersTransformer;
use App\Transformers\Customers\GetCustomerTransformer;
use Illuminate\Http\JsonResponse;
use Laravel\Lumen\Routing\Controller as BaseController;

/**
 * Class CustomerController
 * @package App\Http\Controllers
 * @author Nicodemus Rada <nincorada@gmail.com>
 * @since 2023.12.08
 */
class CustomerController extends BaseController
{
    /**
     * Customer repository instace
     * @var CustomerRepository
     */
    private CustomerRepository $customerRepository;

    /**
     * @var GetAllCustomersTransformer
     */
    private GetAllCustomersTransformer $getAllCustomersTransformer;

    /**
     * @var GetCustomerTransformer
     */
    private GetCustomerTransformer $getCustomerTransformer;

    /**
     * CustomerController constructor
     * @param CustomerRepository $customerRepository
     */
    public function __construct(
        CustomerRepository $customerRepository,
        GetAllCustomersTransformer $getAllCustomersTransformer,
        GetCustomerTransformer $getCustomerTransformer
    )
    {
        $this->customerRepository = $customerRepository;
        $this->getAllCustomersTransformer = $getAllCustomersTransformer;
        $this->getCustomerTransformer = $getCustomerTransformer;
    }
    
    /**
     * Returns all customers stored in json
     * @return JsonResponse
     */
    public function getAllCustomers(): JsonResponse
    {
        $customers = $this->customerRepository->getAllCustomers([
            'firstName',
            'lastName',
            'email',
            'country'
        ]);

        return $this->getAllCustomersTransformer
            ->response($customers)
            ->transform();
    }

    /**
     * Returns a customer by id in json
     * @return JsonResponse
     * @throws
     */
    public function getCustomerById(int $customerId): JsonResponse
    {
        $customer = $this->customerRepository->getCustomerById($customerId, [
            'firstName',
            'lastName',
            'email',
            'userName',
            'gender',
            'country',
            'city',
            'phone'
        ]);

        return $this->getCustomerTransformer
            ->response($customer)
            ->transform();
    }
}
