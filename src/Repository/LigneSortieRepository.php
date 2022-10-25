<?php

namespace App\Repository;

use App\Entity\LigneSortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LigneSortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method LigneSortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method LigneSortie[]    findAll()
 * @method LigneSortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LigneSortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LigneSortie::class);
    }

    // /**
    //  * @return LigneSortie[] Returns an array of LigneSortie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LigneSortie
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
