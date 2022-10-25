<?php

namespace App\Repository;

use App\Entity\LigneRMensuel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LigneRMensuel|null find($id, $lockMode = null, $lockVersion = null)
 * @method LigneRMensuel|null findOneBy(array $criteria, array $orderBy = null)
 * @method LigneRMensuel[]    findAll()
 * @method LigneRMensuel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LigneRMensuelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LigneRMensuel::class);
    }

    // /**
    //  * @return LigneRMensuel[] Returns an array of LigneRMensuel objects
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
    public function findOneBySomeField($value): ?LigneRMensuel
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
