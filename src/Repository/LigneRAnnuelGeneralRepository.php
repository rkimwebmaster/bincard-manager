<?php

namespace App\Repository;

use App\Entity\LigneRAnnuelGeneral;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LigneRAnnuelGeneral|null find($id, $lockMode = null, $lockVersion = null)
 * @method LigneRAnnuelGeneral|null findOneBy(array $criteria, array $orderBy = null)
 * @method LigneRAnnuelGeneral[]    findAll()
 * @method LigneRAnnuelGeneral[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LigneRAnnuelGeneralRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LigneRAnnuelGeneral::class);
    }

    // /**
    //  * @return LigneRAnnuelGeneral[] Returns an array of LigneRAnnuelGeneral objects
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
    public function findOneBySomeField($value): ?LigneRAnnuelGeneral
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
