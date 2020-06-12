<?php

namespace App\Repository;

use App\Entity\SkateParks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SkateParks|null find($id, $lockMode = null, $lockVersion = null)
 * @method SkateParks|null findOneBy(array $criteria, array $orderBy = null)
 * @method SkateParks[]    findAll()
 * @method SkateParks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SkateParksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SkateParks::class);
    }

    // /**
    //  * @return SkateParks[] Returns an array of SkateParks objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SkateParks
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
