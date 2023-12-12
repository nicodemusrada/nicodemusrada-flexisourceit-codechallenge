<?php
declare(strict_types=1);

use App\Repositories\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Tests\TestCase;

/**
 * Class CustomerControllerTest
 * @package Tests
 * @author Nicodemus Rada <nincorada@gmail.com>
 * @since 2023.12.08
 */
class CustomerControllerTest extends TestCase
{

    /**
     * Testing getAllCustomers with success result
     */
    public function test_getAllCustomers_API_returns_customer_data_successfully(): void
    {
        $mockEntityManager = $this->createMock(EntityManagerInterface::class);
        $mockRepository = $this->getMockBuilder(CustomerRepository::class)
            ->setConstructorArgs([$mockEntityManager])
            ->getMock();

        $customers = [
            [
                'firstName' => 'TestFirst',
                'lastName'  => 'TestLast',
                'email'     => 'TestFirstTestLast@email.com',
                'country'   => 'Australia'
            ],
            [
                'firstName' => 'TestSecond',
                'lastName'  => 'TestSecond',
                'email'     => 'TestSecondTestSecond@email.com',
                'country'   => 'Australia'
            ]
        ];

        $mockRepository->expects($this->once())
            ->method('getAllCustomers')
            ->with(['firstName', 'lastName', 'email', 'country'])
            ->willReturn($customers);

        $this->app->instance(CustomerRepository::class, $mockRepository);

        $response = $this->call('GET', '/api/customers');

        $this->assertEquals(200, $response->status());
        $this->assertEquals([
            'data' => [
                [
                    'name'    => 'TestFirst TestLast',
                    'email'   => 'TestFirstTestLast@email.com',
                    'country' => 'Australia'
                ],
                [
                   'name'    => 'TestSecond TestSecond',
                   'email'   => 'TestSecondTestSecond@email.com',
                   'country' => 'Australia'
                ]
            ]
        ], json_decode(json_encode($response->getData()), true));
    }

    /**
     * Testing getAllCustomers with success result but no stored data
     */
    public function test_getAllCustomers_API_returns_empty_data_successfully(): void
    {
        $mockEntityManager = $this->createMock(EntityManagerInterface::class);
        $mockRepository = $this->getMockBuilder(CustomerRepository::class)
            ->setConstructorArgs([$mockEntityManager])
            ->getMock();

        $customers = [];

        $mockRepository->expects($this->once())
            ->method('getAllCustomers')
            ->with(['firstName', 'lastName', 'email', 'country'])
            ->willReturn($customers);

        $this->app->instance(CustomerRepository::class, $mockRepository);

        $response = $this->call('GET', '/api/customers');

        $this->assertEquals(200, $response->status());
        $this->assertEquals(['data' => []], json_decode(json_encode($response->getData()), true));
    }

    /**
     * Testing getCustomerById with success result
     */
    public function test_getCustomerById_API_returns_customer_data_successfully(): void
    {
        $mockEntityManager = $this->createMock(EntityManagerInterface::class);
        $mockRepository = $this->getMockBuilder(CustomerRepository::class)
            ->setConstructorArgs([$mockEntityManager])
            ->getMock();

        $customer =  [
            'firstName' => 'FirstName',
            'lastName'  => 'LastName',
            'email'      => 'test@gmail.com',
            'userName'   => 'username',
            'gender'     => 'male',
            'country'    => 'Australia',
            'city'       => 'City',
            'phone'      => '0000-0000',
        ];

        $fieldsParam = [
            'firstName',
            'lastName',
            'email',
            'userName',
            'gender',
            'country',
            'city',
            'phone'
        ];
        
        $iCustomerId = 1;

        $mockRepository->expects($this->once())
            ->method('getCustomerById')
            ->with($this->equalTo(1), $this->equalTo($fieldsParam))
            ->willReturn($customer);

        $this->app->instance(CustomerRepository::class, $mockRepository);

        $response = $this->call('GET', '/api/customers/' . $iCustomerId);

        $this->assertEquals(200, $response->status());
        $this->assertEquals([
            'data' => [
                'name'     => 'FirstName LastName',
                'email'    => 'test@gmail.com',
                'username' => 'username',
                'gender'   => 'male',
                'country'  => 'Australia',
                'city'     => 'City',
                'phone'    => '0000-0000'
            ]
        ], json_decode(json_encode($response->getData()), true));
    }

    /**
     * Testing getCustomerById returns 404 
     */
    public function test_getCustomerById_API_returns_error_response_requesting_non_existing_user(): void
    {
        $mockEntityManager = $this->createMock(EntityManagerInterface::class);
        $mockRepository = $this->getMockBuilder(CustomerRepository::class)
            ->setConstructorArgs([$mockEntityManager])
            ->getMock();

        $customer =  [];

        $fieldsParam = [
            'firstName',
            'lastName',
            'email',
            'userName',
            'gender',
            'country',
            'city',
            'phone'
        ];
        
        $iCustomerId = 999;

        $mockRepository->expects($this->once())
            ->method('getCustomerById')
            ->with($this->equalTo($iCustomerId), $this->equalTo($fieldsParam))
            ->willThrowException(new EntityNotFoundException());

        $this->app->instance(CustomerRepository::class, $mockRepository);

        $response = $this->call('GET', '/api/customers/' . $iCustomerId);

        $this->assertEquals(404, $response->status());
        $this->assertEquals([
            'error' => [
                'status'  => 404,
                'message' => 'Not Found'
            ]
        ], json_decode(json_encode($response->getData()), true));
    }

    /**
     * Requesting non existing route returns 404
     */
    public function test_nonExisting_API_route_returns_error(): void
    {
        $response = $this->call('GET', '/api/route/not/exist');

        $this->assertEquals(404, $response->status());
        $this->assertEquals([
            'error' => [
                'status'  => 404,
                'message' => 'Not Found'
            ]
        ], json_decode(json_encode($response->getData()), true));
    }
}