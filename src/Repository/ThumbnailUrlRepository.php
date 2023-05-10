<?php

namespace App\Repository;

use App\Entity\ThumbnailImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ThumbnailImage>
 *
 * @method ThumbnailImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method ThumbnailImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method ThumbnailImage[]    findAll()
 * @method ThumbnailImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThumbnailUrlRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ThumbnailImage::class);
    }

    public function save(ThumbnailImage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ThumbnailImage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ThumbnailImage[] Returns an array of ThumbnailImage objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ThumbnailImage
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
