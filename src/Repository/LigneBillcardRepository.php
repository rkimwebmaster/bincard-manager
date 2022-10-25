<?php

namespace App\Repository;

use App\Entity\LigneBillcard;
use App\Entity\Site;
use App\Entity\Produit;
use App\Entity\ProduitSite;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LigneBillcard|null find($id, $lockMode = null, $lockVersion = null)
 * @method LigneBillcard|null findOneBy(array $criteria, array $orderBy = null)
 * @method LigneBillcard[]    findAll()
 * @method LigneBillcard[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LigneBillcardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LigneBillcard::class);
    }

    /**
    * @return LigneBillcard[] Returns an array of LigneBillcard objects
    */
    public function findBySearch(Site $site,ProduitSite $produit,$mouvement,DateTimeInterface $dateDebut,DateTimeInterface $dateFin)
    // public function findBySearch(Site $site,ProduitSite $produit,$mouvement)
    {
        return $this->createQueryBuilder('l')
        ->andWhere('l.site = :val1')
        ->andWhere('l.produitSite = :val2')
       ->andWhere('l.deliveryNoteNumber LIKE :val3')
       ->andWhere('l.date >= :val4')
        ->andWhere('l.date <= :val5')
        ->setParameter('val1', $site)
        ->setParameter('val2', $produit)
        ->setParameter('val3', '%'.$mouvement.'%')
        ->setParameter('val4', $dateDebut)
        ->setParameter('val5', $dateFin)
            ->orderBy('l.createdAt', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?LigneBillcard
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
