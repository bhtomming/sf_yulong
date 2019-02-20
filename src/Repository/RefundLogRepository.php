<?php

namespace App\Repository;

use App\Entity\RefundLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method RefundLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefundLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefundLog[]    findAll()
 * @method RefundLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefundLogRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RefundLog::class);
    }

    // /**
    //  * @return RefundLog[] Returns an array of RefundLog objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RefundLog
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
