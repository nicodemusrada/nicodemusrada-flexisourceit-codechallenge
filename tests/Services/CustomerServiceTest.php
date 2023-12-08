<?php
declare(strict_types=1);

use App\Apis\RandomUser\RandomUserApi;
use App\Constants\ApiConstants;
use App\Exceptions\RandomUserRequestException;
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

        $response = [
            ApiConstants::SUCCESS => true,
            ApiConstants::CODE    => 200,
            ApiConstants::DATA    => [
                [
                    'gender'   => 'male',
                    'name'     => [
                        'first_name' => 'First',
                        'last_name'  => 'Name',
                    ],
                    'location' => [
                        'city'    => 'City',
                        'country' => 'Australia',
                    ],
                    'email'    => 'firstname@email.com',
                    'login'    => [
                        'username' => 'username',
                        'password' => 'password',
                    ],
                    'phone'    => '0000-0000',
                ],
                [
                    'gender'   => 'female',
                    'name'     => [
                        'first_name' => 'Second',
                        'last_name'  => 'Name',
                    ],
                    'location' => [
                        'city'    => 'City 2',
                        'country' => 'Australia',
                    ],
                    'email'    => 'secondname@email.com',
                    'login'    => [
                        'username' => 'username2',
                        'password' => 'password2',
                    ],
                    'phone'    => '0000-0001',
                ],
            ]
        ];

        $apiQueryParams = [
            ApiConstants::COUNT       => 2,
            ApiConstants::NATIONALITY => 'AU',
            ApiConstants::FIELDS      => 'name,email,login,gender,location,phone'
        ];

        $mockApi->expects($this->once())
            ->method('fetchUsers')
            ->with($apiQueryParams)
            ->willReturn($response);
        
        $service = app()->make(CustomerService::class, [
            'userApi' => $mockApi
        ]);
        
        $result = $service->fetchCustomers(2);

        $this->assertEquals($response, $result);
    }

    /**
     * Testing fetchCustomers api failed
     */
    public function test_fetchCustomers_method_when_fetch_failed(): void
    {
        $mockApi = $this->createMock(RandomUserApi::class);

        $exception = new RandomUserRequestException();

        $mockApi->expects($this->once())
            ->method('fetchUsers')
            ->willThrowException($exception);
        
        $service = app()->make(CustomerService::class, [
            'userApi' => $mockApi
        ]);

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
        $mockUserApi = $this->createMock(RandomUserApi::class);

        $mockService = new CustomerService($mockUserApi);

        $mockService = $this->getMockBuilder(CustomerService::class)
                        ->setConstructorArgs([$mockUserApi])
                        ->onlyMethods(['initializeRepository'])
                        ->getMock();

        $customers = [
            [
                "first_name" => "Taylor",
                "last_name"  => "Edwards",
                "email"      => "taylor.edwards@example.com",
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
            [
                "first_name" => "SecondWillie",
                "last_name"  => "SecondStephens",
                "email"      => "willie.stephens@example.com",
                "user_name"  => "Secondwhitetiger259",
                "password"   => "Secondnellie",
                "gender"     => "female",
                "country"    => "Australia",
                "city"       => "Hervey Bay",
                "phone"      => "08-1437-7069"
            ]
        ];

        try {
            $mockService->importCustomers($customers);
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

        $service = new CustomerService($mockUserApi);

        $service = $this->getMockBuilder(CustomerService::class)
            ->setConstructorArgs([$mockUserApi])
            ->onlyMethods(['initializeRepository'])
            ->getMock();

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