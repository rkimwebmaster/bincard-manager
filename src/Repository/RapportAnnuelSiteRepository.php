<?php

namespace App\Repository;

use App\Entity\RapportAnnuelSite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RapportAnnuelSite|null find($id, $lockMode = null, $lockVersion = null)
 * @method RapportAnnuelSite|null findOneBy(array $criteria, array $orderBy = null)
 * @method RapportAnnuelSite[]    findAll()
 * @method RapportAnnuelSite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RapportAnnuelSiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RapportAnnuelSite::class);
    }

    // /**
    //  * @return RapportAnnuelSite[] Returns an array of RapportAnnuelSite objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RapportAnnuelSite
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
