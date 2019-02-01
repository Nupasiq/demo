<?php declare(strict_types=1);

namespace App\DTO;

/**
 * Interface DTOInterface
 */
interface DTOInterface
{
    /**
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * @return string
     */
    public function getEntityName(): string;

    /**
     * @return string
     */
    public function getRequestType(): string;

    /**
     * @param string $requestType
     *
     * @return self
     */
    public function setRequestType(string $requestType): self;
}