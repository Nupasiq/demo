<?php declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Throwable;

/**
 * Class ValidationException
 */
class ValidationException extends AppException
{
    /**
     * @var ConstraintViolationList
     */
    private $violationList;

    /**
     * ValidationException constructor.
     * @param ConstraintViolationList $violations
     * @param string                  $message
     * @param int                     $code
     * @param Throwable|null          $previous
     */
    public function __construct(ConstraintViolationList $violations, string $message = "", int $code = 0, Throwable $previous = null)
    {
        $this->violationList = $violations;
        $this->preparedErrors();
        $message = 'Validation Errors';
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return ConstraintViolationList
     */
    public function getViolations(): ConstraintViolationList
    {
        return $this->violationList;
    }

    /**
     * {@inheritdoc}
     */
    private function preparedErrors(): void
    {
        array_map(function ($violation) {
            /**
             * @var ConstraintViolation $violation
             */
            $error = sprintf('%s: %s', $violation->getPropertyPath(), $violation->getMessage());
            $this->addError($error);
        }, $this->getViolations()->getIterator()->getArrayCopy());
    }
}
