<?php

namespace App\Repository;

use App\Entity\WechatReply;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method WechatReply|null find($id, $lockMode = null, $lockVersion = null)
 * @method WechatReply|null findOneBy(array $criteria, array $orderBy = null)
 * @method WechatReply[]    findAll()
 * @method WechatReply[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WechatReplyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, WechatReply::class);
    }

    // /**
    //  * @return WechatReply[] Returns an array of WechatReply objects
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
    public function findOneBySomeField($value): ?WechatReply
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
