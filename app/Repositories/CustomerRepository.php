<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Constants\CustomerEntityConstants;
use App\Entities\Customer;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

/**
 * Class CustomerRepository
 * @package App\Repositories
 * @author Nicodemus Rada <nincorada@gmail.com>
 * @since 2023.12.08
 */
class CustomerRepository
{

    /**
     * Entity manager instance
     */
    private EntityManagerInterface $entityManager;

    /**
     * Entity used
     * @var string
     */
    private $entity = Customer::class;

    /**
     * CustomerRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Insert or update customers in the database
     * @param array $customers
     */
    public function insertOrUpdateCustomers(array $customers): void
    {
        $existingCustomers = $this->entityManager
            ->getRepository($this->entity)
            ->findBy([CustomerEntityConstants::EMAIL => array_column($customers, CustomerEntityConstants::EMAIL)]);

        $existingEntitiesByEmail = [];
        foreach ($existingCustomers as $existingEntity) {
            $existingEntitiesByEmail[$existingEntity->getEmail()] = $existingEntity;
        }

        foreach ($customers as $customer) {
            $currentEntity = $existingEntitiesByEmail[$customer[CustomerEntityConstants::EMAIL]] ?? null;

            if (is_null($currentEntity)) {
                $currentEntity = new Customer();
                $currentEntity->setEmail($customer[CustomerEntityConstants::EMAIL]);
            }

            $currentEntity->setFirstName($customer[CustomerEntityConstants::FIRST_NAME]);
            $currentEntity->setLastName($customer[CustomerEntityConstants::LAST_NAME]);
            $currentEntity->setGender($customer[CustomerEntityConstants::GENDER]);
            $currentEntity->setUserName($customer[CustomerEntityConstants::USERNAME]);
            $hashedPassword = md5($customer[CustomerEntityConstants::PASSWORD]);
            $currentEntity->setPassword($hashedPassword);
            $currentEntity->setCountry($customer[CustomerEntityConstants::COUNTRY]);
            $currentEntity->setCity($customer[CustomerEntityConstants::CITY]);
            $currentEntity->setPhone($customer[CustomerEntityConstants::PHONE]);

            $this->entityManager->persist($currentEntity);
        }   

        $this->entityManager->flush();
    }

    /**
     * Gets all customer available in storage with specific fields
     * @param array $fields
     * @return array
     */
    public function getAllCustomers(array $fields): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('c.' . implode(', c.', $fields))->from($this->entity, 'c');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Gets a specific customer by id with specific fields
     * @param int $customerId
     * @param array $fields
     * @throws EntityNotFoundException
     * @return array
     */
    public function getCustomerById(int $customerId, array $fields): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
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