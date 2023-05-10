<?php

namespace App\Repository;

use App\Entity\PornStar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PornStar>
 *
 * @method PornStar|null find($id, $lockMode = null, $lockVersion = null)
 * @method PornStar|null findOneBy(array $criteria, array $orderBy = null)
 * @method PornStar[]    findAll()
 * @method PornStar[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PornStarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PornStar::class);
    }

    public function save(PornStar $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PornStar $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return PornStar[] Returns an array of PornStar objects
     */
    public function getAllWithOffsetAndLimit($offset, $limit): array
    {
        if($offset > $limit) {
            throw new \InvalidArgumentException('Offset cannot be larger than the limit');
        }

        return $this->createQueryBuilder('p')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }
}
