<?php declare(strict_types=1);

namespace App\Services\DataManager;

use App\Entity\Blog;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use App\DTO\DTOInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Persistence\ObjectRepository;

/**
 * Class AbstractDataManager
 */
abstract class AbstractDataManager
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var DTOInterface
     */
    private $dto;

    /**
     * AbstractDataManager constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param DTOInterface $dto
     *
     * @return User | Blog | null
     */
    abstract public function execute(DTOInterface $dto);

    /**
     * @return User | Blog
     */
    abstract protected function prepareGet();

    /**
     * @return User | Blog
     */
    abstract protected function prepareCGet();

    /**
     * @return User | Blog
     */
    abstract protected function preparePost();

    /**
     * @return User | Blog
     */
    abstract protected function preparePut();

    /**
     * @return User | Blog
     */
    abstract protected function prepareDelete();

    /**
     * @return Blog|User
     */
    protected function actionTypeDesider()
    {
        switch (true) {
            case $this->getRequestType() === Request::METHOD_GET:
                $action = ($this->getDto()->getId() > 0) ? $this->prepareGet() : $this->prepareCGet();

                return $action;
            case $this->getRequestType() === Request::METHOD_POST:
                return $this->preparePost();
            case $this->getRequestType() === Request::METHOD_PUT:
                return $this->preparePut();
            case $this->getRequestType() === Request::METHOD_DELETE:
                return $this->prepareDelete();
        }
    }

    /**
     * @param string $className
     *
     * @return ObjectRepository|EntityRepository
     */
    protected function getRepository(string $className)
    {
        return $this->em->getRepository($className);
    }

    /**
     * @return string
     */
    protected function getRequestType() : string
    {
        return $this->dto->getRequestType();
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager(): EntityManager
    {
        return $this->em;
    }

    /**
     * @return DTOInterface
     */
    protected function getDto(): DTOInterface
    {
        return $this->dto;
    }

    /**
     * @param DTOInterface $dto
     */
    protected function setDto(DTOInterface $dto)
    {
        $this->dto = $dto;
    }

    /**
     * @param User | Blog $entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function persist($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
    }

    /**
     * @param User | Blog $entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function remove($entity)
    {
        $this->em->remove($entity);
        $this->em->flush();
    }
}
