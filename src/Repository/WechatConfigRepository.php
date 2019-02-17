<?php

namespace App\Repository;

use App\Entity\WechatConfig;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method WechatConfig|null find($id, $lockMode = null, $lockVersion = null)
 * @method WechatConfig|null findOneBy(array $criteria, array $orderBy = null)
 * @method WechatConfig[]    findAll()
 * @method WechatConfig[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WechatConfigRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, WechatConfig::class);
    }

    // /**
    //  * @return WechatConfig[] Returns an array of WechatConfig objects
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
    public function findOneBySomeField($value): ?WechatConfig
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
