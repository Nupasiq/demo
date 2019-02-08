<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Role
 * @ORM\Entity
 * @ORM\Table("role")
 */
class Role
{
    const ROLE_ADMIN = 1;

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
     * @ORM\Column(type="string", name="role", length=256, nullable=false)
     */
    private $role;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\AccessRight", cascade={"persist"})
     ** @ORM\JoinTable(name="role_to_access_right",
     *     joinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="ar_id", referencedColumnName="id")})
     */
    private $roleToAr;

    /**
     * Role constructor.
     */
    public function __construct()
    {
        $this->roleToAr = new ArrayCollection();
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
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param string $role
     *
     * @return self
     */
    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getRoleToAr(): Collection
    {
        return $this->roleToAr;
    }

    /**
     * @param AccessRight $accessRight
     *
     * @return self
     */
    public function addRoleToAr(AccessRight $accessRight): self
    {
        if (!$this->roleToAr->contains($accessRight)) {
            $this->roleToAr->add($accessRight);
        }

        return $this;
    }

    /**
     * @param AccessRight $accessRight
     *
     * @return self
     */
    public function removeRoleToAr(AccessRight $accessRight): self
    {
        if ($this->roleToAr->contains($accessRight)) {
            $this->roleToAr->removeElement($accessRight);
        }

        return $this;
    }
}
