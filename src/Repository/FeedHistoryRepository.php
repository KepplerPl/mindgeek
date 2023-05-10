<?php

namespace App\Repository;

use App\Entity\FeedHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FeedHistory>
 *
 * @method FeedHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method FeedHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method FeedHistory[]    findAll()
 * @method FeedHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FeedHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FeedHistory::class);
    }

    public function save(FeedHistory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FeedHistory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return FeedHistory[] Returns an array of FeedHistory objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    public function getLatestEntry(): ?FeedHistory
    {
        return $this->createQueryBuilder('f')
            ->orderBy('f.created_at', 'desc')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
