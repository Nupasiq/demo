<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class DtoAccessRight
 * @ORM\Entity(repositoryClass="App\Repository\DtoAccessRightRepository")
 * @ORM\Table("dto_access_right")
 */
class DtoAccessRight
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false, options={"unsigned"=true})
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
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\AccessRight", cascade={"persist"})
     * @ORM\JoinTable(name="dto_to_access_right",
     *     joinColumns={@ORM\JoinColumn(name="dto_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="ar_id", referencedColumnName="id")})
     */
    private $dtoToAr;

    /**
     * DtoAccessRight constructor.
     */
    public function __construct()
    {
        $this->dtoToAr = new ArrayCollection();
    }

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
     * @return ArrayCollection
     */
    public function getDtoToAr()
    {
        return $this->dtoToAr;
    }

    /**
     * @param AccessRight $accessRight
     *
     * @return DtoAccessRight
     */
    public function addDtoToAr(AccessRight $accessRight): self
    {
        if (!$this->dtoToAr->contains($accessRight)) {
            $this->dtoToAr->add($accessRight);
        }

        return $this;
    }

    /**
     * @param AccessRight $accessRight
     *
     * @return DtoAccessRight
     */
    public function removeDtoToArr(AccessRight $accessRight): self
    {
        if ($this->dtoToAr->contains($accessRight)) {
            $this->dtoToAr->removeElement($accessRight);
        }

        return $this;
    }
}
