<?php

namespace App\Repository;

use App\Entity\WeChat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method WeChat|null find($id, $lockMode = null, $lockVersion = null)
 * @method WeChat|null findOneBy(array $criteria, array $orderBy = null)
 * @method WeChat[]    findAll()
 * @method WeChat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WeChatRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, WeChat::class);
    }

    // /**
    //  * @return WeChat[] Returns an array of WeChat objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WeChat
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
