<?php

namespace App\Repository;

use App\Entity\PointsSummary;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PointsSummary|null find($id, $lockMode = null, $lockVersion = null)
 * @method PointsSummary|null findOneBy(array $criteria, array $orderBy = null)
 * @method PointsSummary[]    findAll()
 * @method PointsSummary[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PointsSummaryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PointsSummary::class);
    }

    // /**
    //  * @return PointsSummary[] Returns an array of PointsSummary objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PointsSummary
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
