<?php

namespace App\Repository;

use App\Entity\LigneControle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LigneControle|null find($id, $lockMode = null, $lockVersion = null)
 * @method LigneControle|null findOneBy(array $criteria, array $orderBy = null)
 * @method LigneControle[]    findAll()
 * @method LigneControle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LigneControleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LigneControle::class);
    }

    // /**
    //  * @return LigneControle[] Returns an array of LigneControle objects
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
    public function findOneBySomeField($value): ?LigneControle
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
