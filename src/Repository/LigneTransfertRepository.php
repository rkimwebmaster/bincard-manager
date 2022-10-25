<?php

namespace App\Repository;

use App\Entity\LigneTransfert;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LigneTransfert|null find($id, $lockMode = null, $lockVersion = null)
 * @method LigneTransfert|null findOneBy(array $criteria, array $orderBy = null)
 * @method LigneTransfert[]    findAll()
 * @method LigneTransfert[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LigneTransfertRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LigneTransfert::class);
    }

    // /**
    //  * @return LigneTransfert[] Returns an array of LigneTransfert objects
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
    public function findOneBySomeField($value): ?LigneTransfert
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
