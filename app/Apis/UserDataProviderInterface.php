<?php

namespace App\Apis;

/**
 * Interface UserDataProviderInterface
 * @package App\Apis
 * @author Nicodemus Rada <nincorada@gmail.com>
 * @since 2023.12.08
 */
interface UserDataProviderInterface
{
    /**
     * Fetch user data based on specified criteria
     * @param array $queryParams
     * @return array
     */
    public function fetchUsers(array $queryParams): array;

    /**
     * Formats user data into a format accepted by Service
     * @param array $users
     * @return array
     */
    public function formatUserData(array $users): array;
}