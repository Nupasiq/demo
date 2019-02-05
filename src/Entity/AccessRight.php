<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class AccessRight
 * @ORM\Entity
 * @ORM\Table("access_right")
 */
class AccessRight
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
     * @ORM\Column(type="string", name="name", nullable=false, length=256)
     */
    private $name;

    /**
     * @var AccessRightType
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\AccessRightType")
     * @ORM\JoinColumn(name="ar_type_id", referencedColumnName="id", nullable=false)
     */
    private $type;

    /**
     * @var AccessRightAction
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\AccessRightAction")
     * @ORM\JoinColumn(name="ar_action_id", referencedColumnName="id", nullable=false)
     */
    private $action;

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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return AccessRightType
     */
    public function getType(): AccessRightType
    {
        return $this->type;
    }

    /**
     * @param AccessRightType $type
     *
     * @return self
     */
    public function setType(AccessRightType $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return AccessRightAction
     */
    public function getAction(): AccessRightAction
    {
        return $this->action;
    }

    /**
     * @param AccessRightAction $action
     *
     * @return self
     */
    public function setAction(AccessRightAction $action): AccessRight
    {
        $this->action = $action;

        return $this;
    }
}
