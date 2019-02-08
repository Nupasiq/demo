<?php declare(strict_types=1);

namespace App\Services\DataManager;

use App\DTO\BlogDTO;
use App\DTO\DTOInterface;
use App\Entity\Blog;
use App\Entity\Topic;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class BlogDataManager
 */
class BlogDataManager extends AbstractDataManager
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * BlogDataManager constructor.
     * @param EntityManager         $em
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(EntityManager $em, TokenStorageInterface $tokenStorage)
    {
        parent::__construct($em);
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param DTOInterface $dto
     *
     * @return Blog | Blog[]
     */
    public function execute(DTOInterface $dto)
    {
        $this->setDto($dto);
        $result = $this->actionTypeDesider();

        return $result;
    }

    /**
     * @return Blog
     */
    protected function prepareGet()
    {
        return $this->getEntityManager()->getRepository(Blog::class)->find($this->getDto()->getId());
    }

    /**
     * @return Blog[]
     */
    protected function prepareCGet(): array
    {
        return $this->getEntityManager()->getRepository(Blog::class)->findAll();
    }

    /**
     * @return Blog
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \ReflectionException
     */
    protected function preparePost(): Blog
    {
        $blog = new Blog();
        $this->setEntityData($blog);
        $this->persist($blog);

        return $blog;
    }

    /**
     * @return Blog
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \ReflectionException
     */
    protected function preparePut(): Blog
    {
        /**
         * @var Blog $blog
         */
        $blog = $this->getEntityManager()->getRepository(Blog::class)->find($this->getDto()->getId());
        $this->setEntityData($blog);
        $this->persist($blog);

        return $blog;
    }

    /**
     * @return null
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function prepareDelete()
    {
        /**
         * @var Blog $blog
         */
        $blog = $this->getEntityManager()->getRepository(Blog::class)->find($this->getDto()->getId());
        $this->remove($blog);

        return null;
    }

    /**
     * @return BlogDTO
     */
    protected function getDto(): DTOInterface
    {
        return parent::getDto();
    }

    /**
     * @param Blog $blog
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \ReflectionException
     */
    private function setEntityData(Blog $blog): void
    {
        $reflectionEntity = new \ReflectionClass($blog);

        foreach ($this->getDto()->toArray() as $property => $value) {
            if (!is_null($value)) {
                switch (true) {
                    case 'topic' === $property:
                        $reflectionEntity->getMethod(sprintf("set%s", $property))->invoke($blog, $this->prepareTopic());
                        break;
                    default:
                        if ($reflectionEntity->hasMethod(sprintf("set%s", $property))) {
                            $reflectionEntity->getMethod(sprintf("set%s", $property))->invoke($blog, $value);
                        }

                }
            }
        }
        if ($this->getDto()->getRequestType() === Request::METHOD_POST) {
            /**
             * @var User $user
             */
            $user = $this->tokenStorage->getToken()->getUser();
            $blog->setOwner($user);
        }
    }

    /**
     * @return Topic
     *
     * @throws \Doctrine\ORM\ORMException
     */
    private function prepareTopic(): Topic
    {
        return $this->getEntityManager()->getReference(Topic::class, $this->getDto()->topic);
    }
}
