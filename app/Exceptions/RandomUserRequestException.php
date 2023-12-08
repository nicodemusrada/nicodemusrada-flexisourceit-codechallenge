<?php
declare(strict_types=1);

namespace App\Exceptions;

use Exception;

/**
 * Enum Gender
 * @package App\Enums
 * @author Nicodemus Rada <nincorada@gmail.com>
 * @since 2023.12.08
 */
class RandomUserRequestException extends Exception
{
    /**
    * RandomUserRequestException constructor.
    * @param string $message
    * @param int    $code
    */
    public function __construct(int $code = 422, string $message = 'Request to randomuser api failed.')
    {
        parent::__construct($message, $code);
    }
}