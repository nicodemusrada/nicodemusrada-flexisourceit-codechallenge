<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App\Apis\RandomUser\RandomUserApi;
use App\Repositories\CustomerRepository;
use App\Services\CustomerService;
use Illuminate\Console\Command;
use Exception;

/**
 * Class ImportCustomerCommand
 * @package App\Console\Commands
 * @author Nicodemus Rada <nincorada@gmail.com>
 * @since 2023.12.08
 */
class ImportCustomerCommand extends Command
{

    /**
     * Name and signiture of the console command
     * Default count of customers to be imported is 100
     * @var string
     */
    protected $signature = 'customers:import {--count=100}';

    /**
     * Console command description
     * @var string
     */
    protected $description = 'Import users from radomuser.me API that have Austrailian nationality then store them in database.';

    /**
     * @var CustomerService
     */
    private CustomerService $customerService;

    /**
     * ImportCustomerCommand constructor
     */
    public function __construct()
    {
        parent::__construct();
        $userApi = new RandomUserApi();
        $this->customerService = new CustomerService($userApi);
    }

    /**
     * Command execution
     */
    public function handle(): void
    {
        $count = (int) $this->option('count');
       
        try {
            $this->info('Now fetching Australian customer data');
            $customers = $this->customerService->fetchCustomers($count);

            $this->info('Storing fetched Australian customer\'s data');
            $this->customerService->importCustomers($customers);

            $this->info('Customers successfully imported.');
        } catch (Exception $exception) {
            $this->error($exception->getMessage());
        }
    }
}