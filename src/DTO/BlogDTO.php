<?php declare(strict_types=1);

namespace App\DTO;

use App\Entity\Blog;

/**
 * Class BlogDTO
 */
class BlogDTO implements DTOInterface
{
    /**
     * @var int
     */
    private $id = 0;

    /**
     * @var string
     */
    private $requestType;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $description;

    /**
     * @var int
     */
    public $topic;

    /**
     * @var bool
     */
    public $isPublished;

    /**
     * @return int|null
     */
    public function getId(): ?int
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
    public function getEntityName(): string
    {
        return Blog::class;
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
     * @return array
     */
    public function toArray(): array
    {
        return (array) $this;
    }
}
