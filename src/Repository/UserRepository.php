<?php declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class UserRepository
 */
class UserRepository extends EntityRepository
{
    /**
     * @param int $roleId
     *
     * @return mixed
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByRoleId(int $roleId)
    {
        return $this->createQueryBuilder('u')
                ->join('u.roles', 'r')
               ->where('r.id = :roleId')
               ->setParameter('roleId', $roleId)
               ->setMaxResults(1)
               ->getQuery()
               ->getOneOrNullResult();
    }
}