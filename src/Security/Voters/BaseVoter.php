<?php declare(strict_types=1);

namespace App\Security\Voters;

use App\DTO\BlogDTO;
use App\DTO\DTOInterface;
use App\DTO\UserDTO;
use App\Entity\DtoAccessRight;
use App\Entity\AccessRight;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class BaseVoter
 */
class BaseVoter extends Voter
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ArrayCollection
     */
    private $dtoAccessRight;

    /**
     * @var array
     */
    private $userAccessRightList;

    /**
     * @var UserDTO | BlogDTO
     */
    private $dto;

    /**
     * @var bool
     */
    protected $isCan;

    /**
     * @var User
     */
    protected $user;

    /**
     * BaseVoter constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param string $attribute
     * @param mixed  $subject
     *
     * @return bool
     */
    protected function supports($attribute, $subject): bool
    {
        return false;
    }

    /**
     * @param string         $attribute
     * @param mixed          $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        return false;
    }

    /**
     * @param DTOInterface   $subject
     * @param TokenInterface $token
     */
    protected function init(DTOInterface $subject, TokenInterface $token): void
    {
        $this->user = $token->getUser();
        $this->userAccessRightList = $this->user->getAccessRights();
        $this->dto = $subject;
        $className = get_class($this->dto);
        $dtoEntity = $this->em->getRepository(DtoAccessRight::class)->findOneBy(['name' => $className]);
        $this->dtoAccessRight = $dtoEntity->getDtoToAr();
    }

    /**
     * @param int $actionId
     */
    protected function isActionAllowed(int $actionId)
    {
        $dtoAccessRightList = $this->getDtoAccessRightList($actionId, $this->dto->getRequestType());
        $dtoAccessRightId = reset($dtoAccessRightList);
        $this->isCan = !$dtoAccessRightId ? true : in_array($dtoAccessRightId, $this->userAccessRightList);
    }

    /**
     * @param int $id
     *
     * @return object|null
     */
    protected function getEntityById(int $id)
    {
        return $this->em->getRepository($this->dto->getEntityName())->find($id);
    }

    /**
     * @param int    $actionId
     * @param string $actionType
     *
     * @return array
     */
    private function getDtoAccessRightList(int $actionId, string $actionType)
    {
        return $this->dtoAccessRight->filter(function (AccessRight $accessRight) use ($actionId, $actionType) {
            if ($accessRight->getAction()->getId() === $actionId && $accessRight->getType()->getName() === $actionType) {
                return $accessRight;
            }
        })->map(function (AccessRight $accessRight) {
            return $accessRight->getId();
        })->toArray();
    }
}