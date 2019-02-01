<?php declare(strict_types=1);

namespace App\Services\DataManager;

use App\DTO\DTOInterface;
use App\DTO\UserDTO;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserDataManager
 */
class UserDataManager extends AbstractDataManager
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * UserDataManager constructor.
     * @param EntityManager                $em
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(EntityManager $em, UserPasswordEncoderInterface $passwordEncoder)
    {
        parent::__construct($em);
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param DTOInterface $dto
     *
     * @return User | User[] | null
     */
    public function execute(DTOInterface $dto)
    {
        $this->setDto($dto);
        $result = $this->actionTypeDesider();

        return $result;
    }

    /**
     * @return User
     */
    protected function prepareGet()
    {
        return $this->getRepository(User::class)->find($this->getDto()->getId());
    }

    /**
     * @return User|User[]
     */
    protected function prepareCGet()
    {
        return $this->getRepository(User::class)->findAll();
    }

    /**
     * @return User
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \ReflectionException
     */
    protected function preparePost()
    {
        $user = new User();

        $this->setEntityData($user);
        $this->persist($user);

        return $user;
    }

    /**
     * @return User
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \ReflectionException
     */
    protected function preparePut()
    {
        /**
         * @var User $user
         */
        $user = $this->getEntityManager()->getRepository(User::class)->find($this->getDto()->getId());
        $this->setEntityData($user);
        $this->persist($user);

        return $user;
    }

    /**
     * @return User
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function prepareDelete()
    {
        /**
         * @var User $user
         */
        $user = $this->getEntityManager()->getRepository(User::class)->find($this->getDto()->getId());
        $user->setIsActive(false);
        $this->persist($user);

        return $user;
    }

    /**
     * @return UserDTO
     */
    protected function getDto(): DTOInterface
    {
        return parent::getDto();
    }

    /**
     * @param User $user
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \ReflectionException
     */
    private function setEntityData(User $user)
    {
        $reflectionEntity = new \ReflectionClass($user);

        foreach ($this->getDto()->toArray() as $property => $value) {
            if (!is_null($value)) {
                switch (true) {
                    case 'roles' === $property:
                        $reflectionEntity->getMethod(sprintf("set%s", $property))->invoke($user, $this->prepareRoles());
                        break;
                    case 'password' === $property:
                        $reflectionEntity->getMethod(sprintf("set%s", $property))->invoke($user, $this->preparePassword($user));
                        break;
                    default:
                        if ($reflectionEntity->hasMethod(sprintf("set%s", $property))) {
                            $reflectionEntity->getMethod(sprintf("set%s", $property))->invoke($user, $value);
                        }

                }
            }
        }
    }

    /**
     * @param User $user
     *
     * @return string
     */
    private function preparePassword(User $user)
    {
        $user->setSalt(is_null($user->getSalt()) ? '123' : $user->getSalt());

        return $this->passwordEncoder->encodePassword($user, $this->getDto()->password);
    }


    /**
     * @return array
     *
     * @throws \Doctrine\ORM\ORMException
     */
    private function prepareRoles()
    {
        $roles = [];
        $roleList = $this->getDto()->roles;
        foreach ($roleList as $roleId) {
            array_push($roles, $this->getEntityManager()->getReference(Role::class, $roleId));
        }

        return $roles;
    }
}
