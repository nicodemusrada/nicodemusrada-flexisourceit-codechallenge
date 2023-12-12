<?php
declare(strict_types=1);

namespace App\Providers;

use App\Apis\RandomUser\RandomUserApi;
use App\Apis\UserDataProviderInterface;
use App\Repositories\CustomerRepository;
use App\Services\CustomerService;
use Carbon\Laravel\ServiceProvider;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class AppServiceProvider
 * @package App\Providers
 * @author Nicodemus Rada <nincorada@gmail.com>
 * @since 2023.12.12
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bind RandomUserApi as concrete implementation of the UserDataProviderInterface
     * Bind CustomerRepository to a closure that instantiates it with EntityManagerInterface
     * Bind CustomerService to a closure that instantiates it with dependencies resolved from the container
     */
    public function register()
    {
        $this->app->bind(UserDataProviderInterface::class, RandomUserApi::class);

        $this->app->bind(CustomerRepository::class, function ($app) {
            $entityManager = $app->make(EntityManagerInterface::class);
            return new CustomerRepository($entityManager);
        });

        $this->app->bind(CustomerService::class, function($app) {
            $userApi = $app->make(UserDataProviderInterface::class);
            $customerRepository = $app->make(CustomerRepository::class);
            return new CustomerService($userApi, $customerRepository);
        });
    }
}