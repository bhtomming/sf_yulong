<?php

namespace App\Repository;

use App\Entity\PointsLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PointsLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method PointsLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method PointsLog[]    findAll()
 * @method PointsLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PointsLogRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PointsLog::class);
    }

    // /**
    //  * @return PointsLog[] Returns an array of PointsLog objects
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
    public function findOneBySomeField($value): ?PointsLog
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
