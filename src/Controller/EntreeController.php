<?php

namespace App\Controller;

use App\Entity\Entree;
use App\Entity\Site;
use App\Entity\Produit;
use App\Entity\Fournisseur;
use App\Entity\LigneEntree;
use App\Entity\ProduitSite;
use App\Entity\RMensuelSite;
use App\Entity\VerrouMouvement;
use App\Form\EntreeType;
use App\Form\EntreeReuseType;
use App\Repository\EntreeRepository;
use App\Repository\FournisseurRepository;
use App\Repository\LigneEntreeRepository;
use App\Repository\ProduitSiteRepository;
use App\Repository\RMensuelSiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 *@IsGranted("IS_AUTHENTICATED_FULLY")
 * @Route("/entree")
 */
class EntreeController extends AbstractController
{

    /**
     * @Route("/crationEntree", name="creation_entree", methods={"GET"})
     */
    public function crationEntree(FournisseurRepository $fournisseurRepository): Response
    {
        //verifier que l'utilisateurs a le privillege de faires des entrées 
        //verifier que l'utilisateurs a le privillege de faires des entrées 
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('warning', 'Vous devez être connecté.');

            return $this->redirectToRoute('accueil');
        }
        if ($user) {
            $site = $user->getSiteActif();
            if (!$site) {
                //$this->addFlash('warning', 'Votre site n\'est pas un warehouse. uniquement un warehouse avec ce privillege.');

                return $this->redirectToRoute('app_logout');
            }
            $check = $site->getIsWarehouse();
            if ($check == false) {
                $this->addFlash('warning', 'Votre site n\'est pas un warehouse. uniquement un warehouse avec ce privillege.');

                return $this->redirectToRoute('entree_index');
            }
        }

        //verifier s'il existe au moin un fournisseur  et un produit
        $fournisseurs = $this->getDoctrine()->getRepository(Fournisseur::class)->findAll();
        $produits = $this->getDoctrine()->getRepository(Produit::class)->findAll();
        //$sites = $siteRepository->findAll();
        if (!$fournisseurs) {
            if (!$produits) {
                $this->addFlash('warning', 'Aucun fournisseur, ni de produit dans la base. Enregistrer d\'abord un produit et un fournisseur. ');

                return $this->redirectToRoute('entree_index');
            }
        }
        if (!$fournisseurs) {
            $this->addFlash('warning', 'Aucun fournisseur dans la base. Enregistrer d\'abord un fournisseur. ');

            return $this->redirectToRoute('entree_index');
        }
        if (!$produits) {
            $this->addFlash('warning', 'Aucun produit dans la base. Enregistrer d\'abord un produit. ');

            return $this->redirectToRoute('entree_index');
        }
        return $this->render('entree/creationEntree.html.twig', [
            'fournisseurs' => $fournisseurRepository->findAll(),
        ]);
    }

    /**
     * @Route("/", name="entree_index", methods={"GET"})
     */
    public function index(EntreeRepository $entreeRepository): Response
    {
        $siteActif = $this->getUser()->getSiteActif();

        return $this->render('entree/index.html.twig', [
            'entrees' => $entreeRepository->findBy(
                ['siteReception' => $siteActif,],
                ['date' => 'DESC']
            ),
            'type' => 'normale',
        ]);
    }


    /**
     * @Route("/{id}/new", name="entree_new", methods={"GET","POST"})
     */
    public function new(Request $request, Fournisseur $fournisseur, RMensuelSiteRepository $repo): Response
    {
        //verifier que l'utilisateurs a le privillege de faires des entrées 
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('warning', 'Vous devez être connecté.');

            return $this->redirectToRoute('accueil');
        }
        if ($user) {
            $site = $user->getSiteActif();
            $check = $site->getIsWarehouse();
            if ($check == false) {
                $this->addFlash('warning', 'Votre site n\'est pas un warehouse. uniquement un warehouse avec ce privillege.');

                return $this->redirectToRoute('entree_index');
            }
        }
        //verifier s'il existe au moin un fournisseur  et un site créée et un produit
        $sites = $this->getDoctrine()->getRepository(Site::class)->findAll();
        $produits = $this->getDoctrine()->getRepository(Produit::class)->findAll();
        //$sites = $siteRepository->findAll();

        if (!$sites) {
            $this->addFlash('warning', 'Aucun site dans la base. Enregistrer d\'abord un site. ');

            return $this->redirectToRoute('entree_index');
        }
        if (!$produits) {
            $this->addFlash('warning', 'Aucun produit dans la base. Enregistrer d\'abord un produit. ');

            return $this->redirectToRoute('entree_index');
        }

        $fournisseurs = $this->getDoctrine()->getRepository(Fournisseur::class)->findAll();
        if (!$produits) {
            $this->addFlash('warning', 'Aucun fournisseur dans la base. Enregistrer d\'abord un fournisseur. ');

            return $this->redirectToRoute('entree_index');
        }
        $siteActif = $this->getUser()->getSiteActif();
        $entree = new Entree($fournisseur, $siteActif);
        $entree->setUser($this->getUser());
        $form = $this->createForm(EntreeType::class, $entree);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //verifier que ce mois n'est pas verrouiller 
            $date = $entree->getDate();
            $mois = $date->format('m');
            $annee = $date->format('Y');
            ///verifier les verrous 
            $verouMouvement = $this->checkVerrou($mois, $annee, $repo);
            ///fin checking des verrou 
            if ($verouMouvement) {
                $this->addFlash('info', 'Ce mois est déjà verouiller');
                return $this->redirectToRoute('entree_index');
            }
            ////////////
            $entityManager = $this->getDoctrine()->getManager();
            /// creer les produits sites correspondants aux entrees sur les lignes 
            $ligneEntrees = $entree->getLigneEntrees();

            //verifier les doublons sur le entree 
            //$ligneEntrees = $entree->getLigneEntrees();
            $tableau = array();
            foreach ($ligneEntrees as $ligne1) {
                $tableau[] = $ligne1->getProduit()->getId();
            }
            $array_unique = array_unique($tableau);

            if ($array_unique != $tableau) {
                $this->addFlash('danger', 'Vous ne pouvez faire entrer deux fois le même produit du même fournisseur sur le même bon.');

                return $this->redirectToRoute('entree_index');
            }
            ///fin check doublon 
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
                    // $entityManager->persist($ligne->getLigneBillcard());
                } else {
                    $produitSite = new ProduitSite();
                    $produitSite->setQuantite($quantite);
                    $produitSite->setSite($siteActif);
                    $produit = $ligne->getProduit();
                    $produit->setQuantite($produit->getQuantite() + $quantite);
                    $produitSite->setProduit($produit);
                    if ($siteActif->getIsWarehouse()) {
                        $produitSite->setIsProduitDepot(true);
                    }
                    $ligne->setProduitSite($produitSite);
                    $ligne->updateLigneBillcard();
                    $entree->addLigneEntree($ligne);

                    $entityManager->persist($produit);
                    // $entityManager->persist($ligne->getLigneBillcard());
                }
            }
            $entityManager->persist($entree);
            $entityManager->flush();
            $this->addFlash('success', 'Opération réussie. ');

            return $this->redirectToRoute('entree_index');
        }

        return $this->render('entree/new.html.twig', [
            'entree' => $entree,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/newEntreeSpeciale", name="entree_new_entree_speciale", methods={"GET","POST"})
     */
    public function newEntreeSpeciale(Request $request): Response
    {
        $siteActif = $this->getUser()->getSiteActif();
        $entree = new Entree(null, $siteActif);
        $entree->setIsEntreeSpeciale(true);
        $form = $this->createForm(EntreeSpecialeType::class, $entree);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            dd($entree->getIsEntreeSpeciale);
            $entityManager->persist($entree);
            $entityManager->flush();
            $this->addFlash('success', 'Opération réussie. ');

            return $this->redirectToRoute('entree_index');
        }

        return $this->render('entree/new.html.twig', [
            'entree' => $entree,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="entree_show", methods={"GET"})
     */
    public function show(Entree $entree): Response
    {

        return $this->render('entree/show.html.twig', [
            'entree' => $entree,
        ]);
    }

    /**
     * @Route("/{id}/edit/{sup}", name="entree_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Entree $entree): Response
    {

        $siteActif = $this->getUser()->getSiteActif();

        //initialisation des variables de session et creation de l'ancienne valeurs 
        $session = $request->getSession();
        if ($session->has('oldLigneEntrees')) {
            $oldLigneEntrees = $session->get('oldLigneEntrees');
        } else {
            $oldLigneEntrees = new ArrayCollection();
            foreach ($entree->getLigneEntrees() as $ligne) {
                $oldLigneEntrees->add($ligne);
            }
            $session->set('oldLigneEntrees', $oldLigneEntrees);
        }
        // fin initialisation et creation ancienne valeur 
        $form = $this->createForm(EntreeType::class, $entree, []);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            //verifier les doublons sur le entree 
            $ligneEntrees = $entree->getLigneEntrees();
            $tableau = array();
            foreach ($ligneEntrees as $ligne1) {
                $tableau[] = $ligne1->getProduit()->getId();
            }
            $array_unique = array_unique($tableau);
            if ($array_unique != $tableau) {
                $this->addFlash('danger', 'Vous ne pouvez faire entrer deux fois le même produit du même fournisseur sur le même bon.');

                return $this->redirectToRoute('entree_index');
            }
            //fin ckeck doublon 
            if (!$session->has('oldLigneEntrees')) {
                $this->addFlash('warning', 'L\'ancienne valeur est non stockée.');
            } else {
                $oldLigneEntrees = $session->get('oldLigneEntrees');
                //// mise a jour des produits site et general 
                foreach ($ligneEntrees as $ligne) {
                    $quantite = $ligne->getQuantite();
                    $proligne = $ligne->getProduit();
                    //verifier si le produit qui entre est déjà dans le site actuel 
                    $checkProduitSite = $this->getDoctrine()->getRepository(ProduitSite::class)->findOneBy(['site' => $siteActif, 'produit' => $proligne]);
                    if ($checkProduitSite) {
                        $produit = $checkProduitSite->getProduit();
                        ///verifier si le produit figurer sur l'ancienne objet et faire les calculs y afferent 
                        // sinon faire les calculs sans en tenir compte 
                        foreach ($oldLigneEntrees as $ligneEntree) {
                            $produitSiteOld = $ligneEntree->getProduitSite();
                            $produitOld = $ligneEntree->getProduit();
                            $quantiteOld = $ligneEntree->getQuantite();
                            if ($produitOld == $produit) {
                                $checkProduitSite->setQuantite($checkProduitSite->getQuantite() - $quantiteOld);
                                $produit->setQuantite($produit->getQuantite() - $quantiteOld);
                            }
                        }
                        $checkProduitSite->setQuantite($checkProduitSite->getQuantite() + $quantite );
                        $produit->setQuantite($produit->getQuantite() + $quantite);  


                        $ligne->setProduitSite($checkProduitSite);
                        $ligne->updateLigneBillcard();
                        $entree->addLigneEntree($ligne);

                        // $entityManager->persist($produit);
                        // $entityManager->persist($checkProduitSite);
                        // $entityManager->persist($ligne->getLigneBillcard());
                    } else {
                        $produitSite = new ProduitSite();
                        $produitSite->setQuantite($quantite);
                        $produitSite->setSite($siteActif);
                        $produit = $ligne->getProduit();
                        $produit->setQuantite($produit->getQuantite() + $quantite);
                        $produitSite->setProduit($produit);
                        if ($siteActif->getIsWarehouse()) {
                            $produitSite->setIsProduitDepot(true);
                        }
                        $ligne->setProduitSite($produitSite);
                        $ligne->updateLigneBillcard();
                        $entree->addLigneEntree($ligne);

                        $entityManager->persist($produit);
                        // $entityManager->persist($ligne->getLigneBillcard());
                    }
                }
                //// fin update des quantite produits 
                $session->remove('oldLigneEntrees');
            }
            // dd('en dehors  ');

            $entityManager->persist($entree);
            $entityManager->flush();
            $this->addFlash('success', 'Opération réussie. ');
            return $this->redirectToRoute('entree_index');
        }

        return $this->render('entree/edit.html.twig', [
            'entree' => $entree,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="entree_delete", methods={"DELETE","POST","GET"})
     */
    public function delete(Request $request, Entree $entree): Response
    {
        if ($this->isCsrfTokenValid('delete' . $entree->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();

            /// mettre à jour les quantites des produits et produists sites 
            $ligneEntrees = $entree->getLigneEntrees();
            foreach ($ligneEntrees as $ligne) {
                $produitSite = $ligne->getProduitSite();
                $produit = $produitSite->getProduit();

                $quantiteEntree = $ligne->getQuantite();

                $produitSite->setQuantite($produitSite->getQuantite() - $quantiteEntree);
                $produit->setQuantite($produit->getQuantite() - $quantiteEntree);

                $entityManager->persist($produit);
                $entityManager->persist($produitSite);
            }
            $entityManager->remove($entree);
            $entityManager->flush();
            $this->addFlash('success', 'Opération réussie. ');
        }
        return $this->redirectToRoute('entree_index');
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
