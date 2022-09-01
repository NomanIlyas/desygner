<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\Mapping\MappingException;

class AbstractRepository extends EntityRepository
{
    /**
     * @param $entity
     * @param bool $andFlush
     */
    public function persist($entity, $andFlush = true)
    {
        $this->getEntityManager()->persist($entity);
        if ($andFlush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param $entity
     * @param bool $andFlush
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function merge($entity, $andFlush = true)
    {
        $this->getEntityManager()->merge($entity);
        if ($andFlush) {
            $this->getEntityManager()->flush();
        }
    }

    public function flush()
    {
        $this->getEntityManager()->flush();
    }

    /**
     * @param $entity
     * @param bool $andFlush
     */
    public function remove($entity, $andFlush = true)
    {
        $this->getEntityManager()->remove($entity);
        if ($andFlush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @throws MappingException
     */
    public function clear()
    {
        $this->getEntityManager()->clear();
    }

    public function transactional(callable $callable)
    {
        return $this->getEntityManager()->transactional($callable);
    }
}
