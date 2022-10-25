<?php

namespace App\Repository;

use App\Entity\LigneRAnnuelSite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LigneRAnnuelSite|null find($id, $lockMode = null, $lockVersion = null)
 * @method LigneRAnnuelSite|null findOneBy(array $criteria, array $orderBy = null)
 * @method LigneRAnnuelSite[]    findAll()
 * @method LigneRAnnuelSite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LigneRAnnuelSiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LigneRAnnuelSite::class);
    }

    // /**
    //  * @return LigneRAnnuelSite[] Returns an array of LigneRAnnuelSite objects
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
    public function findOneBySomeField($value): ?LigneRAnnuelSite
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
