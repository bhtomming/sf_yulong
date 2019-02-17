<?php

namespace App\Repository;

use App\Entity\Memeber;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Memeber|null find($id, $lockMode = null, $lockVersion = null)
 * @method Memeber|null findOneBy(array $criteria, array $orderBy = null)
 * @method Memeber[]    findAll()
 * @method Memeber[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MemeberRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Memeber::class);
    }

    // /**
    //  * @return Memeber[] Returns an array of Memeber objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Memeber
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
