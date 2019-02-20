<?php

namespace App\Repository;

use App\Entity\Assess;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Assess|null find($id, $lockMode = null, $lockVersion = null)
 * @method Assess|null findOneBy(array $criteria, array $orderBy = null)
 * @method Assess[]    findAll()
 * @method Assess[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssessRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Assess::class);
    }

    // /**
    //  * @return Assess[] Returns an array of Assess objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Assess
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
