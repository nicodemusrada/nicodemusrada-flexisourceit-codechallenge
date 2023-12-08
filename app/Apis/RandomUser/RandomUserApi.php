<?php
declare(strict_types=1);

namespace App\Apis\RandomUser;

use App\Apis\BaseApi;
use App\Apis\UserDataProviderInterface;
use App\Constants\ApiConstants;
use App\Constants\CustomerEntityConstants;
use App\Constants\RandomUserConstants;
use App\Exceptions\RandomUserRequestException;
use Exception;

/**
 * Class RandomUserApi
 * @package Apis\RadnomUser
 * @author Nicodemus Rada <nincorada@gmail.com>
 * @since 2023.12.08
 */
class RandomUserApi extends BaseApi implements UserDataProviderInterface
{
    /**
     * RandomUserApi constructor
     */
    public function __construct()
    {
        $this->setBaseUri('https://randomuser.me/api');
    }

    /**
     * Fetch users
     * @param array $queryParams
     * @throws RandomUserRequestException
     * @return array
     */
    public function fetchUsers(array $queryParams): array
    {
        $response = $this->request(ApiConstants::GET, '/', [
            RandomUserConstants::RESULTS     => $queryParams[ApiConstants::COUNT],
            RandomUserConstants::NATIONALITY => $queryParams[ApiConstants::NATIONALITY],
            RandomUserConstants::INCLUDE     => $queryParams[ApiConstants::FIELDS]
        ]);
        if ($response[ApiConstants::SUCCESS] === false) {
            throw new RandomUserRequestException();
        }


        return $this->formatUserData($response);
    }

    /**
     * Formats the cusomers before returning result
     * @param array $customers
     * @return array
     */
    public function formatUserData(array $customers): array
    {
        return array_map(function ($item) {
            return [
                CustomerEntityConstants::FIRST_NAME => $item[RandomUserConstants::NAME][RandomUserConstants::NAME_FIRST],
                CustomerEntityConstants::LAST_NAME  => $item[RandomUserConstants::NAME][RandomUserConstants::NAME_LAST],
                CustomerEntityConstants::EMAIL      => $item[RandomUserConstants::EMAIL],
                CustomerEntityConstants::USERNAME   => $item[RandomUserConstants::LOGIN][RandomUserConstants::LOGIN_USERNAME],
                CustomerEntityConstants::PASSWORD   => $item[RandomUserConstants::LOGIN][RandomUserConstants::LOGIN_PASSWORD],
                CustomerEntityConstants::GENDER     => $item[RandomUserConstants::GENDER],
                CustomerEntityConstants::COUNTRY    => $item[RandomUserConstants::LOCATION][RandomUserConstants::LOCATION_COUNTRY],
                CustomerEntityConstants::CITY       => $item[RandomUserConstants::LOCATION][RandomUserConstants::LOCATION_CITY],
                CustomerEntityConstants::PHONE      => $item[RandomUserConstants::PHONE]
            ];
        }, $customers[ApiConstants::DATA]);
    }
}