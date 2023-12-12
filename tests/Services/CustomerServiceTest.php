<?php
declare(strict_types=1);

use App\Apis\RandomUser\RandomUserApi;
use App\Constants\ApiConstants;
use App\Exceptions\RandomUserRequestException;
use App\Repositories\CustomerRepository;
use App\Services\CustomerService;
use Tests\TestCase;

/**
 * Class CustomerServiceTest
 * @package Tests
 * @author Nicodemus Rada <nincorada@gmail.com>
 * @since 2023.12.08
 */
class CustomerServiceTest extends TestCase
{
    /**
     * Testing fetchCustomers method when api request is success
     */
    public function test_fetchCustomers_method_when_response_success(): void
    {
        $mockApi = $this->createMock(RandomUserApi::class);
        $mockCustomerRepository = $this->createMock(CustomerRepository::class);

        $response = [
            [
                'first_name' => 'First',
                'last_name'  => 'Name',
                'email'      => 'firstname@email.com',
                'user_name'  => 'username',
                'password'   => 'password',
                'gender'     => 'male',
                'country'    => 'Australia',
                'city'       => 'City',
                'phone'      => '0000-0000', 
            ],
            [
                'first_name' => 'Second',
                'last_name'  => 'Name',
                'email'      => 'secondname@email.com',
                'user_name'  => 'username',
                'password'   => 'password',
                'gender'     => 'male',
                'country'    => 'Australia',
                'city'       => 'City',
                'phone'      => '0000-0000', 
            ],
        ];

        $userCount = 2;

        $apiQueryParams = [
            ApiConstants::COUNT       => $userCount,
            ApiConstants::NATIONALITY => 'AU',
            ApiConstants::FIELDS      => 'name,email,login,gender,location,phone'
        ];

        $mockApi->expects($this->once())
            ->method('fetchUsers')
            ->with($apiQueryParams)
            ->willReturn($response);
        
        $service = new CustomerService($mockApi, $mockCustomerRepository);
        
        $result = $service->fetchCustomers($userCount);

        $this->assertEquals($response, $result);
    }

    /**
     * Testing fetchCustomers api failed
     */
    public function test_fetchCustomers_method_when_fetch_failed(): void
    {
        $mockApi = $this->createMock(RandomUserApi::class);
        $mockCustomerRepository = $this->createMock(CustomerRepository::class);

        $exception = new RandomUserRequestException();

        $mockApi->expects($this->once())
            ->method('fetchUsers')
            ->willThrowException($exception);
        
        $service = new CustomerService($mockApi, $mockCustomerRepository);

        try {
            $result = $service->fetchCustomers(5);
            $this->fail('Expected exception was not thrown');
        } catch (RandomUserRequestException $exception) {
            $this->assertEquals('Request to randomuser api failed.', $exception->getMessage());
        }
    }

    /**
     * Testing importCustomers when there are no errors
     */
    public function test_importCustomers_method_success(): void
    {
        $customers = [
            [
                "first_name" => "Taylor",
                "last_name"  => "Edwards",
                "email"      => "existing.email@example.com",
                "user_name"  => "brownostrich149",
                "password"   => "mick",
                "gender"     => "female",
                "country"    => "Australia",
                "city"       => "Kalgoorlie",
                "phone"      => "05-1834-2822"
            ],
            [
                "first_name" => "Willie",
                "last_name"  => "Stephens",
                "email"      => "willie.stephens@example.com",
                "user_name"  => "whitetiger259",
                "password"   => "nellie",
                "gender"     => "female",
                "country"    => "Australia",
                "city"       => "Hervey Bay",
                "phone"      => "08-1437-7069"
            ],
        ];

        $mockUserApi = $this->createMock(RandomUserApi::class);
        $mockCustomerRepository = $mockCustomerRepository = $this->createMock(CustomerRepository::class);

        $mockCustomerRepository->expects($this->once())
            ->method('insertOrUpdateCustomers');

        $service = new CustomerService($mockUserApi, $mockCustomerRepository);

        try {
            $service->importCustomers($customers);
            $this->assertTrue(true); // Assert that no exception was thrown
        } catch (\Throwable $exception) {
            $this->fail('An exception was thrown: ' . $exception->getMessage());
        }
    }

    /**
     * Testing importCustomers when passed customers are incorrect data format
     */
    public function test_importCustomers_method_failed_with_incorrect_data_format(): void
    {
        $mockUserApi = $this->createMock(RandomUserApi::class);
        $mockCustomerRepository = $mockCustomerRepository = $this->createMock(CustomerRepository::class);
        $mockCustomerRepository->expects($this->once())
            ->method('insertOrUpdateCustomers');

        $service = new CustomerService($mockUserApi, $mockCustomerRepository);

        $incorrectCustomerFormat = [
            [
                "last_name"  => "Edwards",
                "email"      => "taylor.edwards@example.com",
                "user_name"  => "brownostrich149",
                "password"   => "mick",
                "gender"     => "female",
                "country"    => "Australia",
                "city"       => "Kalgoorlie",
                "phone"      => "05-1834-2822"
            ]
        ];
        
        try {
            $service->importCustomers($incorrectCustomerFormat);
        } catch (Exception $exception) {
            $this->assertEquals('Undefined array key "first_name"', $exception->getMessage());
        }
    }
}