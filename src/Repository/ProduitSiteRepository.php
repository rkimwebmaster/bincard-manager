<?php

namespace App\Repository;

use App\Entity\ProduitSite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProduitSite|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProduitSite|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProduitSite[]    findAll()
 * @method ProduitSite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitSiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProduitSite::class);
    }

    // /**
    //  * @return ProduitSite[] Returns an array of ProduitSite objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProduitSite
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
