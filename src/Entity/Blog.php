<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Blog
 *
 * @ORM\Table("blog")
 */
class Blog
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false, options={"unsigned" = true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     *@ORM\Column(type="string", name="title", nullable=false, length=256)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="text", name="description", nullable=false)
     */
    private $description;

    /**
     * @var Topic
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Topic")
     * @ORM\JoinColumn(name="topic_id", referencedColumnName="id", nullable=false)
     */
    private $topic;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $owner;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", name="is_published", nullable=false)
     */
    private $isPublished;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return self
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return self
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Topic
     */
    public function getTopic(): Topic
    {
        return $this->topic;
    }

    /**
     * @param Topic $topic
     *
     * @return self
     */
    public function setTopic(Topic $topic): self
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * @return User
     */
    public function getOwner(): User
    {
        return $this->owner;
    }

    /**
     * @param User $owner
     *
     * @return self
     */
    public function setOwner(User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPublished(): bool
    {
        return $this->isPublished;
    }

    /**
     * @param bool $isPublished
     *
     * @return Blog
     */
    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }

}
