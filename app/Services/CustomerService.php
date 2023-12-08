<?php
declare(strict_types=1);

namespace App\Services;

use App\Apis\UserDataProviderInterface;
use App\Constants\ApiConstants;
use App\Repositories\CustomerRepository;

/**
 * Class CustomerService
 * @package App\Services
 * @author Nicodemus Rada <nincorada@gmail.com>
 * @since 2023.12.08
 */
class CustomerService
{

    /**
     * User Api must be follow the UserdataProviderInterface
     * @var UserDataProviderInterface
     */
    private UserDataProviderInterface $userApi;

    /**
     * Instance of Customer Repository
     * @var CustomerRepository
     */
    private CustomerRepository $customerRepository;

    /**
     * CustomerService constructor
     * @param UserDataProviderInterface $userApi
     */
    public function __construct(UserDataProviderInterface $userApi)
    {
        $this->userApi = $userApi;
    }

    /**
     * Initialize CustomerRepository
     */
    private function initializeRepository()
    {
        $this->customerRepository = new CustomerRepository;
    }

    /**
     * Fetch a number of australian customer data
     * @param int $count
     * @return array 
     */
    public function fetchCustomers(int $count): array
    {
        $queryParams = [
            ApiConstants::COUNT       => $count,
            ApiConstants::NATIONALITY => 'AU',
            ApiConstants::FIELDS      => 'name,email,login,gender,location,phone'
        ];

        return $this->userApi->fetchUsers($queryParams);
    }

    /**
     * Store Customer Data
     * @param array $customers
     */
    public function importCustomers(array $customers): void
    {
        $this->initializeRepository();
        $this->customerRepository->insertOrUpdateCustomers($customers);
    }
}