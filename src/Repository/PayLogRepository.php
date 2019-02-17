<?php

namespace App\Repository;

use App\Entity\PayLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PayLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method PayLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method PayLog[]    findAll()
 * @method PayLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PayLogRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PayLog::class);
    }

    // /**
    //  * @return PayLog[] Returns an array of PayLog objects
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
    public function findOneBySomeField($value): ?PayLog
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
