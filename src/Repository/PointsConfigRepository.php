<?php

namespace App\Repository;

use App\Entity\PointsConfig;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PointsConfig|null find($id, $lockMode = null, $lockVersion = null)
 * @method PointsConfig|null findOneBy(array $criteria, array $orderBy = null)
 * @method PointsConfig[]    findAll()
 * @method PointsConfig[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PointsConfigRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PointsConfig::class);
    }

    // /**
    //  * @return PointsConfig[] Returns an array of PointsConfig objects
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
    public function findOneBySomeField($value): ?PointsConfig
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
