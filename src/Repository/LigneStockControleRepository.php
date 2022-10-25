<?php

namespace App\Repository;

use App\Entity\LigneStockControle;
use App\Entity\Site;
use App\Entity\Produit;
use App\Entity\ProduitSite;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LigneStockControle|null find($id, $lockMode = null, $lockVersion = null)
 * @method LigneStockControle|null findOneBy(array $criteria, array $orderBy = null)
 * @method LigneStockControle[]    findAll()
 * @method LigneStockControle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LigneStockControleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LigneStockControle::class);
    }

    /**
    * @return LigneBillcard[] Returns an array of LigneBillcard objects
    */
    public function findBySearch(Site $site,ProduitSite $produit,$mouvement,DateTimeInterface $dateDebut,DateTimeInterface $dateFin)
    // public function findBySearch(Site $site,ProduitSite $produit,$mouvement)
    {
        if($mouvement==10){
            $mouvement='';
        }

        //dd($mouvement);
        //exit();
        return $this->createQueryBuilder('l')
        ->andWhere('l.site = :val1')
        ->andWhere('l.produitSite = :val2')
       ->andWhere('l.numeroDNN LIKE :val3')
       ->andWhere('l.date >= :val4')
        ->andWhere('l.date <= :val5')
        ->setParameter('val1', $site)
        ->setParameter('val2', $produit)
        ///gerer aussi le cas es mouvements nul 
        ->setParameter('val3', '%'.$mouvement.'%')
        ->setParameter('val4', $dateDebut)
        ->setParameter('val5', $dateFin)
            ->orderBy('l.createdAt', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

        /**
    * @return LigneBillcard[] Returns an array of LigneBillcard objects
    */
    public function findByProduitMouvement(ProduitSite $produit,$mouvement)
    // public function findBySearch(Site $site,ProduitSite $produit,$mouvement)
    {
        return $this->createQueryBuilder('l')
        ->andWhere('l.produitSite = :val2')
       ->andWhere('l.deliveryNoteNumber LIKE :val3')
        ->setParameter('val2', $produit)
        ->setParameter('val3', '%'.$mouvement.'%')
            ->orderBy('l.createdAt', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

            /**
    * @return LigneBillcard[] Returns an array of LigneBillcard objects
    */
    public function findByProduit(ProduitSite $produit)
    // public function findBySearch(Site $site,ProduitSite $produit,$mouvement)
    {
        return $this->createQueryBuilder('l')
        ->andWhere('l.produitSite = :val2')
        ->setParameter('val2', $produit)
            ->orderBy('l.createdAt', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    // /**
    //  * @return LigneStockControle[] Returns an array of LigneStockControle objects
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
    public function findOneBySomeField($value): ?LigneStockControle
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
