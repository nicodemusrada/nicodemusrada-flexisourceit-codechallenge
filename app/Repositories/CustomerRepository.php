<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Constants\CustomerEntityConstants;
use App\Entities\Customer;
use Doctrine\ORM\EntityNotFoundException;
use LaravelDoctrine\ORM\Facades\EntityManager;

/**
 * Class CustomerRepository
 * @package App\Repositories
 * @author Nicodemus Rada <nincorada@gmail.com>
 * @since 2023.12.08
 */
class CustomerRepository
{
    /**
     * Entity used
     * @var string
     */
    private $entity = Customer::class;

    /**
     * Insert or update customers in the database
     * @param array $customers
     */
    public function insertOrUpdateCustomers(array $customers): void
    {
        foreach ($customers as $customer) {
            $currentEntity = EntityManager::getRepository($this->entity)
                ->findOneBy([CustomerEntityConstants::EMAIL => $customer[CustomerEntityConstants::EMAIL]]);
    
            if (is_null($currentEntity) === true) {
                $currentEntity = new Customer();
                $currentEntity->setEmail($customer[CustomerEntityConstants::EMAIL]);
            } else {
                $currentEntity = $currentEntity;
            }
    
            $currentEntity->setFirstName($customer[CustomerEntityConstants::FIRST_NAME]);
            $currentEntity->setLastName($customer[ CustomerEntityConstants::LAST_NAME]);
            $currentEntity->setGender($customer[CustomerEntityConstants::GENDER]);

            $currentEntity->setUserName($customer[CustomerEntityConstants::USERNAME]);
            $hashedPassword = md5($customer[CustomerEntityConstants::PASSWORD]);
            $currentEntity->setPassword($hashedPassword);
            
            $currentEntity->setCountry($customer[CustomerEntityConstants::COUNTRY]);
            $currentEntity->setCity($customer[CustomerEntityConstants::CITY]);
            $currentEntity->setPhone($customer[CustomerEntityConstants::PHONE]);
    
            EntityManager::persist($currentEntity);
        }
    
        EntityManager::flush();
    }

    /**
     * Gets all customer available in storage with specific fields
     * @param array $fields
     * @return array
     */
    public function getAllCustomers(array $fields): array
    {
        $queryBuilder = EntityManager::createQueryBuilder();
        $queryBuilder->select('c.' . implode(', c.', $fields))->from($this->entity, 'c');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Gets a specific customer by id with specific fields
     * @param int $customerId
     * @param array $fields
     * @return array
     */
    public function getCustomerById(int $customerId, array $fields): array
    {
        $queryBuilder = EntityManager::createQueryBuilder();
        $queryBuilder->select('c.' . implode(', c.', $fields))
            ->from($this->entity, 'c')
            ->where('c.id = ' . $customerId);
        
        $customer = data_get($queryBuilder->getQuery()->getResult(), '0', []);
        
        if (empty($customer) === true) {
            throw new EntityNotFoundException();
        } 

        return $customer;
    }
}