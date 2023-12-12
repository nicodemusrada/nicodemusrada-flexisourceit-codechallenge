<?php
declare(strict_types=1);

namespace App\Entities;

use DateTimeImmutable;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="customers")
 * @ORM\HasLifecycleCallbacks
 */
class Customer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(name="first_name", type="string")
     */
    private string $firstName;

     /**
     * @ORM\Column(name="last_name", type="string")
     */
    private string $lastName;

     /**
     * @ORM\Column(type="string")
     */
    private string $email;

     /**
     * @ORM\Column(name="user_name", type="string")
     */
    private string $userName;

     /**
     * @ORM\Column(type="string")
     */
    private string $password;

     /**
     * @ORM\Column(type="string")
     */
    private string $gender;

     /**
     * @ORM\Column(type="string")
     */
    private string $country;

     /**
     * @ORM\Column(type="string")
     */
    private string $city;

     /**
     * @ORM\Column(type="string")
     */
    private string $phone;

     /**
     * @ORM\Column(name="created_at", type="datetime_immutable")
     */
    private DateTimeImmutable $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime_immutable", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist(PrePersistEventArgs $args)
    {
        $this->createdAt = new DateTimeImmutable();
    }

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate(PreUpdateEventArgs $args)
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    /**
     * Set first name
     * @param string $userName
     */
    public function setUserName(string $userName): void
    {
        $this->userName = $userName;
    }

    /**
     * Set first name
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * Set first name
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * Set first name
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * Set first name
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * Set first name
     * @param string $gender
     */
    public function setGender(string $gender): void
    {
        $this->gender = $gender;
    }

    /**
     * Set first name
     * @param string $country
     */
    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    /**
     * Set first name
     * @param string $city
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * Set first name
     * @param string $phone
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }
}