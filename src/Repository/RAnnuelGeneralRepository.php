<?php

namespace App\Repository;

use App\Entity\RAnnuelGeneral;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RAnnuelGeneral|null find($id, $lockMode = null, $lockVersion = null)
 * @method RAnnuelGeneral|null findOneBy(array $criteria, array $orderBy = null)
 * @method RAnnuelGeneral[]    findAll()
 * @method RAnnuelGeneral[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RAnnuelGeneralRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RAnnuelGeneral::class);
    }

    // /**
    //  * @return RAnnuelGeneral[] Returns an array of RAnnuelGeneral objects
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
    public function findOneBySomeField($value): ?RAnnuelGeneral
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
