<?php declare(strict_types=1);

namespace App\Exception;

/**
 * Class AppException
 */
class AppException extends \Exception
{
    /**
     * @var array
     */
    private $errors = [];

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param string $error
     */
    public function addError(string $error): void
    {
        if (!in_array($error, $this->errors)) {
            $this->errors[] = $error;
        }
    }
}