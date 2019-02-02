<?php declare(strict_types=1);

namespace App\DTO;

use App\Entity\User;

/**
 * Class UserDTO
 */
class UserDTO implements DTOInterface
{
    /**
     * @var int
     */
    private $id = 0;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $password;

    /**
     * @var string
     */
    public $firstName;

    /**
     * @var string
     */
    public $lastName;

    /**
     * @var array
     */
    public $roles;

    /**
     * @var bool
     */
    public $isActive;

    /**
     * @var string
     */
    private $requestType;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getRequestType(): string
    {
        return $this->requestType;
    }

    /**
     * @param string $requestType
     *
     * @return DTOInterface
     */
    public function setRequestType(string $requestType): DTOInterface
    {
        $this->requestType = $requestType;

        return $this;
    }

    /**
     * @return string
     */
    public function getEntityName(): string
    {
        return User::class;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return (array) $this;
    }
}
