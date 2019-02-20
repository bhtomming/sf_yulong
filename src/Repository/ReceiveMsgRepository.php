<?php

namespace App\Repository;

use App\Entity\ReceiveMsg;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ReceiveMsg|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReceiveMsg|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReceiveMsg[]    findAll()
 * @method ReceiveMsg[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReceiveMsgRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ReceiveMsg::class);
    }

    // /**
    //  * @return ReceiveMsg[] Returns an array of ReceiveMsg objects
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
    public function findOneBySomeField($value): ?ReceiveMsg
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
