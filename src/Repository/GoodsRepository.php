<?php

namespace App\Repository;

use App\Entity\Goods;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Goods|null find($id, $lockMode = null, $lockVersion = null)
 * @method Goods|null findOneBy(array $criteria, array $orderBy = null)
 * @method Goods[]    findAll()
 * @method Goods[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GoodsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Goods::class);
    }

    public function findByHot()
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.hot = :hot')
            ->setParameter('hot',true)
            ->orderBy('g.sorter','ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByKeyword($keyword)
    {
        return $this->createQueryBuilder('g')
            ->orderBy("g.publishTime","DESC")
            ->andWhere('g.name like :keyword')
            ->setParameter('keyword','%'.$keyword.'%')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    // /**
    //  * @return Goods[] Returns an array of Goods objects
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
    public function findOneBySomeField($value): ?Goods
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
