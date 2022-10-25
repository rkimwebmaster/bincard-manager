<?php

namespace App\Controller;

use App\Entity\LigneTransfert;
use App\Entity\LigneBillcard;
use App\Entity\Transfert;
use App\Entity\Site;
use App\Entity\ProduitSite;
use App\Form\TransfertType;
use App\Repository\RMensuelSiteRepository;
use App\Repository\TransfertRepository;
use App\Repository\SiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 *@IsGranted("IS_AUTHENTICATED_FULLY")
 * @Route("/transfert")
 */
class TransfertController extends AbstractController
{

    /**
     * @Route("/indexEmis", name="transfert_emis_index", methods={"GET"})
     */
    public function indexEmis(TransfertRepository $transfertRepository): Response
    {
        $site = $this->getUser()->getSiteActif();
        return $this->render('transfert/indexEmis.html.twig', [
            'transferts' => $transfertRepository->findBy(['siteEnvoie' => $site]),
        ]);
    }

    /**
     * @Route("/indexRecu", name="transfert_recu_index", methods={"GET"})
     */
    public function indexRecus(TransfertRepository $transfertRepository): Response
    {
        $site = $this->getUser()->getSiteActif();
        return $this->render('transfert/indexRecus.html.twig', [
            'transferts' => $transfertRepository->findBy(['siteReception' => $site]),
        ]);
    }
    /**
     * @Route("/creationTransfert", name="creation_transfert", methods={"GET"})
     */
    public function creationTransfert(SiteRepository $siteRepository): Response
    {
        //se rassurer que le site ne manque pas de produits 
        $site = $this->getUser()->getSiteActif();
        $produitSiteRepository = $this->getDoctrine()->getRepository(ProduitSite::class);
        $produitSites = $produitSiteRepository->findBy(['site' => $site]);

        if (sizeof($produitSites) == 0) {
            $this->addFlash('warning', 'Votre site ne contient aucun produit. ');

            return $this->redirectToRoute('sortie_index');
        }
        return $this->render('transfert/creationTransfert.html.twig', [
            'sites' => $siteRepository->findAll(),
        ]);
    }

    /**
     * @Route("/indexNonValidee", name="transfert_non_valide", methods={"GET"})
     */
    public function indexNonValidee(TransfertRepository $transfertRepository): Response
    {
        $site = $this->getUser()->getSiteActif();
        return $this->render('transfert/index.html.twig', [
            'transferts' => $transfertRepository->findBy(['siteReception' => $site, 'isValidee' => false]),
        ]);
    }
    /**
     * @Route("/", name="transfert_index", methods={"GET"})
     */
    public function index(TransfertRepository $transfertRepository): Response
    {
        $site = $this->getUser()->getSiteActif();
        $transferts = $transfertRepository->findBy(['siteEnvoie' => $site]);
        $transferts2 = $transfertRepository->findBy(['siteReception' => $site]);

        $all = array_merge($transferts, $transferts2);

        return $this->render('transfert/index.html.twig', [
            'transferts' => $all,
        ]);
    }

    /**
     * @Route("/{id}/new", name="transfert_new", methods={"GET","POST"})
     */
    public function new(Request $request, Site $siteReception, RMensuelSiteRepository $repo): Response
    {

        $siteEnvoie = $this->getUser()->getSiteActif();
        $transfert = new Transfert($siteEnvoie, $siteReception);
        $transfert->setUser($this->getUser());
        $form = $this->createForm(TransfertType::class, $transfert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //verifier que ce mois n'est pas verrouiller 
            $date = $transfert->getDate();
            $mois = $date->format('m');
            $annee = $date->format('Y');
            $verouMouvement = $this->checkVerrou($mois, $annee, $repo);
            if ($verouMouvement) {
                $this->addFlash('info', 'Ce mois est déjà verouiller');
                return $this->redirectToRoute('accueil');
            }

            ////////////

            $entityManager = $this->getDoctrine()->getManager();

            $ligneTransferts = $transfert->getLigneTransferts();

            //verifier les doublons sur le entree 
            //$ligneEntrees = $entree->getLigneEntrees();
            $tableau = array();
            foreach ($ligneTransferts as $ligne1) {
                $tableau[] = $ligne1->getProduitSite()->getId();
            }
            $array_unique = array_unique($tableau);

            if ($array_unique != $tableau) {
                $this->addFlash('danger', 'Vous ne pouvez faire des doublons de produit lors de transfert.');

                return $this->redirectToRoute('transfert_index');
            }
            ///fin check doublon 

            $siteEnvoie = $transfert->getSiteEnvoie();
            $siteReception = $transfert->getSiteReception();
            $siteReception->setValidationAttendu(floatval($siteReception->getValidationAttendu()) + floatval(1));

            $entityManager = $this->getDoctrine()->getManager();
            ///mise a jour des quantotes de produitsite de tous les deux sites emeteur et recepteur 

            $ligneTransferts = $transfert->getLigneTransferts();
            foreach ($ligneTransferts as $ligne) {
                $produitSiteEnvoie = $ligne->getProduitSite();
                $quantiteEnvoie = $ligne->getQuantite();
                $quantiteProduitSite = $produitSiteEnvoie->getQuantite();
                ///rejeter si la quantite sortie est superieure a la quantite en stock 
                if ($quantiteEnvoie > $quantiteProduitSite) {
                    $this->addFlash('warning', 'La quantité de ' . $produitSiteEnvoie->getProduit()->getPn() . ' que vous essayez de sortir est supérieure à celle en stock. ');

                    return $this->redirectToRoute('transfert_index');
                }
                $produitSiteEnvoie->setQuantite($produitSiteEnvoie->getQuantite() - $quantiteEnvoie);
                $entityManager->persist($produitSiteEnvoie);
            }
            /////
            $entityManager->persist($siteReception);

            $entityManager->persist($transfert);
            $entityManager->flush();
            $this->addFlash('success', 'Opération réussie. ');

            return $this->redirectToRoute('transfert_index');
        }

        return $this->render('transfert/new.html.twig', [
            'transfert' => $transfert,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/valider", name="transfert_valider", methods={"GET","POST"})
     */
    public function valider(Request $request, Transfert $transfert): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        ///mise a jour des quantotes de produitsite de tous les deux sites emeteur et recepteur 
        $siteReception = $transfert->getSiteReception();
        $siteEnvoie = $transfert->getSiteEnvoie();

        $ligneTransferts = $transfert->getLigneTransferts();
        foreach ($ligneTransferts as $ligne) {
            $produitSiteEnvoie = $ligne->getProduitSite();
            $quantiteEnvoie = $ligne->getQuantite();
            $produit = $produitSiteEnvoie->getProduit();

            $produitSiteReception = $this->getDoctrine()->getRepository(ProduitSite::class)->findOneBy(['site' => $siteReception, 'produit' => $produit]);
            if ($produitSiteReception) {
                $produitSiteReception->setQuantite($produitSiteReception->getQuantite() + $quantiteEnvoie);
            } else {
                $produitSiteReception = new ProduitSite();
                $produitSiteReception->setProduit($produit);
                $produitSiteReception->setQuantite($quantiteEnvoie);
                $produitSiteReception->setSite($siteReception);
                if ($siteReception->getIsWarehouse()) {
                    $produitSiteReception->setIsProduitDepot(true);
                }
            }
            $entityManager->persist($produitSiteReception);
            //$entityManager->persist($produitSiteEnvoie);
            ///c'est a ce niveau que sera instancié la ligne billcard de validation
            // $this->updateLigneBillcard($ligne);
            $ligneTransfert = $ligne;
            $produit = $ligneTransfert->getProduitSite();
            $date = $ligneTransfert->getTransfert()->getDate();
            $machineNumber = '';
            $deliveryNoteNumber = 'TRANSFERT VALIDE ';
            $supllier = 'Createur de reuse ';
            $idNumber = 'rien a reuse';
            $quantityReceived = $ligneTransfert->getQuantite();
            $quantitySold = ' ';
            //a verifer la responsabilite du site 
            $site = $ligneTransfert->getTransfert()->getSiteReception();


            $customer = $ligneTransfert->getTransfert()->getSiteReception()->getDesignation();
            $totalBalance = ' ';

            ///verifier le code ci-dessous 
            $ligneBillcard = new LigneBillcard($produit, $date, $machineNumber, $deliveryNoteNumber, $quantityReceived, $supllier, $customer, $idNumber, $quantitySold, $totalBalance, $site);
            $em = $this->getDoctrine()->getManager();
            $em->persist($ligneBillcard);
        }
        $siteReception->setValidationAttendu(floatval($siteReception->getValidationAttendu()) - floatval(1));

        $validationAttendu = floatval($siteReception->getValidationAttendu());
        if ($validationAttendu < 0) {
            $this->addFlash('warning', 'Cette validation rendrait les validations attendues inférieur à 0. ');
            return $this->redirectToRoute('transfert_index');
        }

        $transfert->setIsValidee(true);
        $entityManager->persist($siteReception);
        $entityManager->persist($transfert);
        $entityManager->flush();
        // dd("le transfert veut de la validation");

        $this->addFlash('success', 'Opération réussie. ');

        return $this->redirectToRoute('transfert_index');
    }

    /**
     * @Route("/{id}", name="transfert_show", methods={"GET"})
     */
    public function show(Transfert $transfert): Response
    {
        return $this->render('transfert/show.html.twig', [
            'transfert' => $transfert,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="transfert_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Transfert $transfert): Response
    {
        //exit(var_dump($transfert->getSiteReception()));
        $form = $this->createForm(TransfertType::class, $transfert, [
            'action' => $this->generateUrl('transfert_new', ['id' => $transfert->getSiteReception()->getId()]),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $siteEnvoie = $transfert->getSiteEnvoie();
            $siteReception = $transfert->getSiteReception();
            $siteReception->setValidationAttendu(floatval($siteReception->getValidationAttendu()) + floatval(1));

            $entityManager = $this->getDoctrine()->getManager();
            ///mise a jour des quantotes de produitsite de tous les deux sites emeteur et recepteur 

            $ligneTransferts = $transfert->getLigneTransferts();
            foreach ($ligneTransferts as $ligne) {
                $produitSiteEnvoie = $ligne->getProduitSite();
                $quantiteEnvoie = $ligne->getQuantite();
                $quantiteProduitSite = $produitSiteEnvoie->getQuantite();
                ///rejeter si la quantite sortie est superieure a la quantite en stock 
                if ($quantiteEnvoie > $quantiteProduitSite) {
                    $this->addFlash('warning', 'La quantité de ' . $produitSiteEnvoie->getProduit()->getPn() . ' que vous essayez de sortir est supérieure à celle en stock. ');

                    return $this->redirectToRoute('transfert_index');
                }
                $produitSiteEnvoie->setQuantite($produitSiteEnvoie->getQuantite() - $quantiteEnvoie);
                $entityManager->persist($produitSiteEnvoie);
            }
            /////
            $entityManager->persist($siteReception);

            $entityManager->persist($transfert);
            $entityManager->flush();
            $this->addFlash('success', 'Opération réussie. ');

            return $this->redirectToRoute('transfert_index');
        }
        //supression du transfert de la base de donnnées 
        $entityManager = $this->getDoctrine()->getManager();

        ///mise a jour des produits sites des deux cotés de site a la suppression 
        $ligneTransferts = $transfert->getLigneTransferts();
        $siteEnvoie = $transfert->getSiteEnvoie();
        $siteReception = $transfert->getSiteReception();
        foreach ($ligneTransferts as $ligne) {
            $quantiteTransfert = $ligne->getQuantite();
            $produitSite = $ligne->getProduitSite();
            $produit = $produitSite->getProduit();
            $produitSiteEnvoie = $this->getDoctrine()->getRepository(ProduitSite::class)->findOneBy(['site' => $siteEnvoie, 'produit' => $produit]);

            $produitSiteReception = $this->getDoctrine()->getRepository(ProduitSite::class)->findOneBy(['site' => $siteReception, 'produit' => $produit]);

            $produitSiteEnvoie->setQuantite($produitSiteEnvoie->getQuantite() + $quantiteTransfert);
            if ($produitSiteReception) {
                $produitSiteReception->setQuantite($produitSiteReception->getQuantite() - $quantiteTransfert);
                $entityManager->persist($produitSiteReception);
            }
            $entityManager->persist($produitSiteEnvoie);
        }
        $siteReception = $transfert->getSiteReception();
        $siteReception->setValidationAttendu($siteReception->getValidationAttendu() - 1);

        $entityManager->persist($siteReception);

        //cloner avant de supprimer 
        $autreTransfert = $transfert;
        $entityManager->remove($transfert);
        $entityManager->flush();
        $this->addFlash('warning', 'Ce transfert a été supprimé de la bse de donnée. terminer la modification et validé. ');

        return $this->render('transfert/edit.html.twig', [
            'transfert' => $autreTransfert,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="transfert_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Transfert $transfert): Response
    {
        if ($this->isCsrfTokenValid('delete' . $transfert->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();

            $siteReception = $transfert->getSiteReception();
            $siteEnvoie = $transfert->getSiteEnvoie();
            $siteReception->setValidationAttendu($siteReception->getValidationAttendu() - 1);
            ///mise a jour des produits sites des deux cotés de site a la suppression 
            $ligneTransferts = $transfert->getLigneTransferts();
            foreach ($ligneTransferts as $ligne) {
                $quantiteTransfert = $ligne->getQuantite();
                $produitSite = $ligne->getProduitSite();
                $produit = $produitSite->getProduit();
                $produitSiteEnvoie = $this->getDoctrine()->getRepository(ProduitSite::class)->findOneBy(['site' => $siteEnvoie, 'produit' => $produit]);

                $produitSiteEnvoie->setQuantite($produitSiteEnvoie->getQuantite() + $quantiteTransfert);

                $produitSiteReception = $this->getDoctrine()->getRepository(ProduitSite::class)->findOneBy(['site' => $siteReception, 'produit' => $produit]);
                if ($produitSiteReception) {
                    $produitSiteReception->setQuantite($produitSiteReception->getQuantite() - $quantiteTransfert);
                    $entityManager->persist($produitSiteReception);
                }


                $entityManager->persist($produitSiteEnvoie);
            }

            $entityManager->persist($siteReception);
            $entityManager->remove($transfert);
            $entityManager->flush();
            $this->addFlash('success', 'Opération réussie. ');
        }

        return $this->redirectToRoute('transfert_index');
    }

    public function updateLigneBillcard(LigneTransfert $ligneTransfert)
    {

        // dd('ceci est un transfert validéé avec confirmation');
        //on va creer deux instance de cette methode pour permettre la modification , voir ci-dessous        
        //enregistrer ligne billcard 

        //    $em->flush();
        dd('france');
        // $ligneTransfert->addLigneBillcard($ligneBillcard);
    }

    public function checkVerrou(int $mois, int $annee, RMensuelSiteRepository $rMensuelSiteRepository)
    {
        $checkRapport = $rMensuelSiteRepository->findBy(['mois' => $mois, 'annee' => $annee, 'isCloture' => true]);
        if ($checkRapport) {
            $this->addFlash('warning', 'Ce mois est déjà cloturé. Vous ne pouvez effectuer des entrées.');
            return $this->redirectToRoute('entree_index');
        }
        return;
    }
}
