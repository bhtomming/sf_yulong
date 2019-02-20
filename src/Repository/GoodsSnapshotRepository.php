<?php

namespace App\Repository;

use App\Entity\GoodsSnapshot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GoodsSnapshot|null find($id, $lockMode = null, $lockVersion = null)
 * @method GoodsSnapshot|null findOneBy(array $criteria, array $orderBy = null)
 * @method GoodsSnapshot[]    findAll()
 * @method GoodsSnapshot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GoodsSnapshotRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GoodsSnapshot::class);
    }

    // /**
    //  * @return GoodsSnapshot[] Returns an array of GoodsSnapshot objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GoodsSnapshot
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
