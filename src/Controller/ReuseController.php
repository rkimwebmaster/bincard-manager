<?php

namespace App\Controller;

use App\Entity\Entree;
use App\Entity\Site;
use App\Entity\Produit;
use App\Entity\Fournisseur;
use App\Entity\LigneEntree;
use App\Entity\ProduitSite;
use App\Form\EntreeType;
use App\Form\EntreeReuseType;
use App\Repository\EntreeRepository;
use App\Repository\FournisseurRepository;
use App\Repository\ProduitSiteRepository;
use App\Repository\RMensuelSiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 *@IsGranted("IS_AUTHENTICATED_FULLY")
 * @Route("/entreeReuse")
 */
class ReuseController extends AbstractController
{
    /**
     * @Route("/reuse", name="reuse")
     */
    public function index(): Response
    {
        return $this->render('reuse/index.html.twig', [
            'controller_name' => 'ReuseController',
        ]);
    }

        /**
     * @Route("/creationReuse", name="creation_reuse", methods={"GET"})
     */
    public function creationReuse(ProduitSiteRepository $produitSiteRepository): Response
    {
        $site = $this->getUser()->getSiteActif();
        $produitSites = $produitSiteRepository->findBy(['site' => $site]);
        //exit(var_dump($produitSites));
        if (sizeof($produitSites) == 0) {
            $this->addFlash('warning', 'Rassurez-vous que votre site est un warehouse et qu\'il a reçu au moins un transfert. ');
        }
        return $this->render('ligne_reuse/creationReuse.html.twig', [
            'produits' => $produitSites,
        ]);
    }

        /**
     * @Route("/indexReuse", name="entree_reuse_index", methods={"GET"})
     */
    public function indexReuse(EntreeRepository $entreeRepository): Response
    {
        $siteActif = $this->getUser()->getSiteActif();

        return $this->render('entree/index.html.twig', [
            'entrees' => $entreeRepository->findBy(['siteReception' => $siteActif, 'isReuse' => true], ['date' => 'DESC']),
            'type'=>'IsReuse',

        ]);
    }


        /**
     * @Route("/{id}/newReuse", name="entree_reuse_new", methods={"GET","POST"})
     */
    public function newReuse(Request $request, ProduitSite $produitSite, RMensuelSiteRepository $repo): Response
    {
        $siteActif = $this->getUser()->getSiteActif();
        $entree = new Entree(null, $siteActif);
        $entree->setUser($this->getUser());
        $entree->setIsReuse(true);
        $entree->setSiteReception($siteActif);

        $ligneEntree = new LigneEntree();
        $ligneEntree->setProduitSite($produitSite);
        $ligneEntree->setProduit($produitSite->getProduit());
        $ligneEntree->setProduitSite($produitSite);
        $entree->addLigneEntree($ligneEntree);

        $form = $this->createForm(EntreeReuseType::class, $entree);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //verifier que ce mois n'est pas verrouiller 
            $date=$entree->getDate();
            $mois = $date->format('m');
            $annee = $date->format('Y');
            $verouMouvement = $this->checkVerrou($mois,$annee,$repo);
            if($verouMouvement){
                $this->addFlash('info','Ce mois est déjà verouiller');
                return $this->redirectToRoute('accueil');
            }
            ////////////

            $entityManager = $this->getDoctrine()->getManager();

            //Check de la quantite 
            $ligneEntrees = $entree->getLigneEntrees();
            foreach ($ligneEntrees as $ligne) {
                $produitSite = $ligne->getProduitSite();

                $quantiteEntree = $ligne->getQuantite();
                $quantiteProduitSite = $produitSite->getQuantite();
                $produitSite->setQuantite($quantiteProduitSite + $quantiteEntree);
                $produit = $produitSite->getProduit();
                $produit->setQuantite($produit->getQuantite() + $quantiteEntree);
                $ligne->setProduit($produit);
                $ligne->updateLigneBillcard();

                $entree->addLigneEntree($ligne);

                // $entityManager->persist($ligne);
                $entityManager->persist($produit);
                $entityManager->persist($produitSite);
            }

            $entityManager->persist($entree);
            $entityManager->flush();

            $this->addFlash('success', 'Opération réussie. ');

            return $this->redirectToRoute('entree_index');
        }

        return $this->render('entree/newReuse.html.twig', [
            'entree' => $entree,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{id}/editReuse", name="entree_reuse_edit", methods={"GET","POST"})
     */
    public function editReuse(Request $request, Entree $entree): Response
    {
        $ligneEntrees=$entree->getLigneEntrees();
        $uniqueLigneEntree=$ligneEntrees[0];
        $produitSite=$uniqueLigneEntree->getProduitSite();
       // dd($uniqueLigneEntree);
        $siteActif = $this->getUser()->getSiteActif();

        $form = $this->createForm(EntreeReuseType::class, $entree, [
            'action' => $this->generateUrl('entree_reuse_new',['id'=>$produitSite->getId()]),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        $entityManager = $this->getDoctrine()->getManager();


        if ($form->isSubmitted() && $form->isValid()) {
            //$entityManager = $this->getDoctrine()->getManager();
            //$entityManager->flush();
            /// creer les produits sites correspondants aux entrees sur les lignes 
            $ligneEntrees = $entree->getLigneEntrees();
            foreach ($ligneEntrees as $ligne) {
                $quantite = $ligne->getQuantite();
                $proligne = $ligne->getProduit();
                $checkProduitSite = $this->getDoctrine()->getRepository(ProduitSite::class)->findOneBy(['site' => $siteActif, 'produit' => $proligne]);
                if ($checkProduitSite) {
                    $checkProduitSite->setQuantite($checkProduitSite->getQuantite() + $quantite);
                    $produit = $checkProduitSite->getProduit();
                    $produit->setQuantite($produit->getQuantite() + $quantite);
                    $ligne->setProduitSite($checkProduitSite);
                    $ligne->updateLigneBillcard();
                    $entree->addLigneEntree($ligne);

                    $entityManager->persist($produit);
                    $entityManager->persist($checkProduitSite);
                    $entityManager->persist($ligne->getLigneBillcard());
                } else {
                    $produitSite = new ProduitSite();
                    $produitSite->setQuantite($quantite);
                    $produitSite->setSite($siteActif);
                    $produit = $ligne->getProduit();
                    $produitSite->setProduit($produit);
                    if ($siteActif->getIsWarehouse()) {
                        $produitSite->setIsProduitDepot(true);
                    }
                    $ligne->setProduitSite($produitSite);
                    $ligne->updateLigneBillcard();
                    $entree->addLigneEntree($ligne);

                    $entityManager->persist($produit);
                    /// $entityManager->persist($checkProduitSite);
                    $entityManager->persist($ligne->getLigneBillcard());

                    // dd('salut dedans else ');

                }
                // $ligne->updateLigneBillcard();
                // $entree->addLigneEntree($ligne); 
                //dd('ssss');

            }
            $entityManager->persist($entree);
            $entityManager->flush();
            $this->addFlash('success', 'Opération réussie. ');

            return $this->redirectToRoute('entree_index');
        }

        $entityManager = $this->getDoctrine()->getManager();

        /// mettre à jour les quantites des produits et produists sites 
        $produitSite = $uniqueLigneEntree->getProduitSite();
        $produit = $produitSite->getProduit();

        $quantiteEntree = $uniqueLigneEntree->getQuantite();

        $produitSite->setQuantite($produitSite->getQuantite() - $quantiteEntree);
        $produit->setQuantite($produit->getQuantite() - $quantiteEntree);

        $entityManager->persist($produit);
        $entityManager->persist($produitSite);
        $entityManager->remove($entree);
        $entityManager->flush();
        $this->addFlash('warning', 'Cette entrée a été supprimé, il sera remis en base après confirmations de votre opération ');

        return $this->render('entree/newReuse.html.twig', [
            'entree' => $entree,
            'form' => $form->createView(),
        ]);
    }

    
    public function checkVerrou(int $mois, int $annee, RMensuelSiteRepository $rMensuelSiteRepository){
        $checkRapport=$rMensuelSiteRepository->findBy(['mois'=>$mois,'annee'=>$annee,'isCloture'=>true]);
        if($checkRapport){
            $this->addFlash('warning','Ce mois est déjà cloturé. Vous ne pouvez effectuer des entrées.');
            return $this->redirectToRoute('entree_index');
        }
        return ;

    }


}
