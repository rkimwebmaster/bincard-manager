<?php

namespace App\Controller;

use App\Entity\Entree;
use App\Entity\LigneRMensuel;
use App\Entity\ProduitSite;
use App\Entity\RMensuelSite;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\Transfert;
use App\Entity\VerrouMouvement;
use App\Form\MensuelType;
use App\Form\RMensuelSiteType;
use App\Repository\RMensuelSiteRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/rmensuelsite")
 */
class RMensuelSiteController extends AbstractController
{
    /**
     * @Route("/", name="r_mensuel_site_index", methods={"GET","POST"})
     */
    public function index(Request $request, RMensuelSiteRepository $rMensuelSiteRepository): Response
    {
        $site = $this->getUser()->getSiteActif();
        $rapports = $rMensuelSiteRepository->findBy(['site' => $site]);
        if (sizeof($rapports) == 0) {
            $this->addFlash('info', 'Aucun rapport créé dans le système. ');
        }

        return $this->render('r_mensuel_site/index.html.twig', [
            'r_mensuel_sites' => $rapports,
        ]);
    }

    /**
     * @Route("/creationRMensuel", name="creation_r_mensuel", methods={"GET","POST"})
     */
    public function creationRMensuel(Request $request, RMensuelSiteRepository $rMensuelSiteRepository): Response
    {
        // $form = $this->createForm(MensuelType::class);
        $form = $this->createForm(MensuelType::class, null, [
            'action' => $this->generateUrl('r_mensuel_site_new'),
            'method' => 'POST',
        ]);
        // $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $data = $form->getData();
            $annee = $data['annee'];
            $mois = $data['mois'];
            dd($data['mois']);
        }
        // dd('dehors');
        return $this->render('r_mensuel_site/creationRapport.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new", name="r_mensuel_site_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $site = $this->getUser()->getSiteActif();

        $mensuel = $request->request->get('mensuel');
        $mois = $mensuel['mois'];
        $annee = $mensuel['annee'];
        // dd($mensuel['mois']);

        //verifier si les parametres existent et afficher le  rapport du mois passé en parametre
        if ($mensuel and $mois and $annee) {
            $checkRapport = $this->getDoctrine()->getRepository(RMensuelSite::class)->findOneBy(['site' => $site, 'annee' => $annee, 'mois' => $mois]);
            if ($checkRapport) {
                return $this->redirectToRoute('r_mensuel_site_show', ['id' => $checkRapport->getId()]);
            } else {
                $this->addFlash('info', 'Le rapport du mois recherché n\'est pas encore créé. ');
                return $this->redirectToRoute('r_mensuel_site_index');
            }
        }
        ///dans le cas contraire lire tous les rapport et prendre celui qui a le plus grand id et incrementer 
        //a partir de lui de 1
        $rapports = $this->getDoctrine()->getRepository(RMensuelSite::class)->findBy(['site' => $site]);
        if ($rapports) {
            $tableau = [];
            foreach ($rapports as $rapport) {
                $tableau[] = $rapport->getId();
            }
            // l'id du plus grand rapport ci-bas 
            $maximum = max($tableau);

            $rapportPasse = $this->getDoctrine()->getRepository(RMensuelSite::class)->find($maximum);

            if ($rapportPasse) {
                if ($rapportPasse->getIsCloture() == false) {
                    $this->addFlash('info', 'Votre tout dernier rapport est non cloturé... Veuillez le cloturer en premier ');
                    return $this->redirectToRoute('r_mensuel_site_show', ['id' => $rapportPasse->getId()]);
                }
            }

            $moisDernierRapport = $rapportPasse->getMois();
            $anneeDernierRapport = $rapportPasse->getAnnee();

            if ($moisDernierRapport == 12) {
                $mois = 1;
                $annee = $anneeDernierRapport + 1;
            } else {
                $mois = $moisDernierRapport + 1;
                $annee = $anneeDernierRapport;
            }
        } else {
            ///initialiser par defaut le mois et l'année , attention lors du deploiement 
            $entreeInitial = $this->getDoctrine()->getRepository(Entree::class)->findOneBy(['siteReception' => $site]);
            $transfertinitial = $this->getDoctrine()->getRepository(Transfert::class)->findOneBy(['siteReception' => $site]);

            $mois = 0;
            $annee = 0;

            if (isset($entreeInitial) and isset($transfertinitial)) {
                if ($entreeInitial->getDate() < $transfertinitial->getDate()) {
                    // dd("Entrée initial antérieur ET TRANSFERT INITIAL");

                    $mois = $entreeInitial->getMois();
                    $annee = $entreeInitial->getAnnee();
                    // $rMensuelSite->setMois($mois);
                    // $rMensuelSite->setAnnee($annee);
                    // $rMensuelSite = new RMensuelSite($site, $annee, $mois);
                } elseif ($entreeInitial->getDate() > $transfertinitial->getDate()) {
                    // dd("Transfert initial antérieur ");
                    $mois = $transfertinitial->getMois();
                    $annee = $transfertinitial->getAnnee();
                }
            } elseif (isset($entreeInitial) and $transfertinitial == null) {
                // dd("entrée initial existe ");

                $mois = $entreeInitial->getMois();
                $annee = $entreeInitial->getAnnee();
                // $rMensuelSite->setMois($mois);
                // $rMensuelSite->setAnnee($annee);
                // $rMensuelSite = new RMensuelSite($site, $annee, $mois);
            } elseif (isset($transfertinitial) and $entreeInitial == null) {
                // dd("Transfert initial existe ");

                $mois = $transfertinitial->getMois();
                $annee = $transfertinitial->getAnnee();
                // $rMensuelSite->setMois($mois);
                // $rMensuelSite->setAnnee($annee);
                // $rMensuelSite = new RMensuelSite($site, $annee, $mois);
            }
        }

        // dd('stop');
        /// a partir d ici commence la genration du rapport, l'instanciaton s est fait plus haut 
        $rMensuelSite = new RMensuelSite($site, $annee, $mois);

        //les operations ci-dessous seront trié en fonction du mois passé en parametre 
        $entrees = $this->getDoctrine()->getRepository(Entree::class)->findBy(['siteReception' => $site, 'mois' => $mois, 'annee' => $annee]);
        if (sizeof($entrees) == 0) {
            $this->addFlash('info', 'Ce site ne contient aucune entrée pour ce mois-ci. ');
        }
        //il faut gerer ci dessous les deux types de ransfert entree et sorties 
        // dd($site);
        $transferts = $this->getDoctrine()->getRepository(Transfert::class)->findBy(['mois' => $mois, 'annee' => $annee]);
        $transfertsEntrant = $this->getDoctrine()->getRepository(Transfert::class)->findBy(['siteReception' => $site, 'mois' => $mois, 'annee' => $annee]);
        $transfertsSortant = $this->getDoctrine()->getRepository(Transfert::class)->findBy(['siteEnvoie' => $site, 'mois' => $mois, 'annee' => $annee]);
        if (sizeof($transfertsEntrant) == 0) {
            $this->addFlash('info', 'Ce site ne contient aucun transfert entrant pour ce mois-ci. ');
        }
        if (sizeof($transfertsSortant) == 0) {
            $this->addFlash('info', 'Ce site ne contient aucun transfert sortant pour ce mois-ci. ');
        }
        $sorties = $this->getDoctrine()->getRepository(Sortie::class)->findBy(['siteEnvoie' => $site, 'mois' => $mois, 'annee' => $annee]);
        if (sizeof($sorties) == 0) {
            $this->addFlash('info', 'Ce site ne contient aucune sortie pour ce mois-ci. ');
        }

        if (sizeof($sorties) == 0 && sizeof($transferts) == 0 && sizeof($entrees) == 0) {
            $this->addFlash('warning', 'Par conséquant, le rapoprt ne peut être généré ');
            return $this->redirectToRoute('accueil');
        }

        ///
        $entityManager = $this->getDoctrine()->getManager();
        $produitSites = $this->getDoctrine()->getRepository(ProduitSite::class)->findBy(['site' => $site]);
        //parcours des produits sites 
        foreach ($produitSites as $produitSite) {
            //a chaque produit site correspond une ligne entree 
            $ligneRMensuel = new LigneRMensuel();
            $ligneRMensuel->setPn($produitSite);

            // prendre la qte finale du  rqpport pqsse qui serq l initiqle du present 
            if (isset($rapportPasse)) {
                $ligneRapportPasses = $rapportPasse->getLigneRMensuels();
                foreach ($ligneRapportPasses as $ligneRapport) {
                    if ($ligneRapport->getPn() == $produitSite) {
                        $ligneRMensuel->setQuantiteInitiale($ligneRapport->getQuantiteFinale());
                        break;
                    }
                }
            }

            $totalEntree = 0.0;
            $totalSortie = 0.0;
            ///parcours de toutes les entrées 
            /// ci dessous la variables qui va sommer les entrées doit etre implementée
            if (isset($entrees)) {
                foreach ($entrees as $entree) {
                    if ($entree->getIsEntreeSpeciale()) {
                        // dd('entree speciale');
                        $ligneEntrees = $entree->getLigneEntrees();
                        foreach ($ligneEntrees as $ligneEntree) {
                            if ($ligneEntree->getProduitSite() == $produitSite) {
                                //affecter la qte entree 
                                $ligneRMensuel->setQuantiteEntreeSpeciale($ligneRMensuel->getQuantiteEntreeSpeciale() + $ligneEntree->getQuantite());
                                $totalEntree = $totalEntree + $ligneRMensuel->getQuantiteEntree();
                            }
                            ////test
                        }
                    } elseif ($entree->getIsReuse()) {
                        $ligneEntrees = $entree->getLigneEntrees();
                        foreach ($ligneEntrees as $ligneEntree) {
                            if ($ligneEntree->getProduitSite() == $produitSite) {
                                //affecter la qte entree 
                                $ligneRMensuel->setQuantiteEntreeReuse($ligneRMensuel->getQuantiteEntreeReuse() + $ligneEntree->getQuantite());
                                $totalEntree = $totalEntree + $ligneRMensuel->getQuantiteEntree();
                            }
                        }
                    } elseif ($entree->getIsEntreeSpeciale() == false and $entree->getIsReuse() == false) {
                        $ligneEntrees = $entree->getLigneEntrees();
                        foreach ($ligneEntrees as $ligneEntree) {
                            if ($ligneEntree->getProduitSite() == $produitSite) {
                                //affecter la qte entree 
                                $ligneRMensuel->setQuantiteEntree($ligneRMensuel->getQuantiteEntree() + $ligneEntree->getQuantite());
                                $totalEntree = $totalEntree + $ligneRMensuel->getQuantiteEntree();
                            }

                            // dd($ligneEntree->getQuantite().' et '.$ligneRMensuel->getQuantiteEntreeTransfert());
                            // dd($ligneRMensuel->getQuantiteEntreeTransfert());
                        }
                    }
                }
            }
            //fin entree

            /// ci dessous la variables qui va sommer les sorties doit etre implementée
            if (isset($sorties)) {
                foreach ($sorties  as $sortie) {
                    //gestion des sorties normales  
                    if ($sortie->getIsDamage() == false and $sortie->getIsSortieSpeciale() == false) {
                        $ligneSorties = $sortie->getLigneSorties();
                        foreach ($ligneSorties as $ligneSortie) {
                            if ($ligneSortie->getProduitSite() == $produitSite) {
                                //affecter la qte entree 
                                $ligneRMensuel->setSortieClient($ligneRMensuel->getSortieClient() + $ligneSortie->getQuantite());
                                $totalSortie = $totalSortie + $ligneRMensuel->getSortieClient();
                            }
                        }
                    }
                    if ($sortie->getIsSortieSpeciale()) {
                        $ligneSorties = $sortie->getLigneSorties();
                        foreach ($ligneSorties as $ligneSortie) {
                            if ($ligneSortie->getProduitSite() == $produitSite) {
                                //affecter la qte entree 
                                $ligneRMensuel->setSortieSpeciale($ligneRMensuel->getSortieSpeciale() + $ligneSortie->getQuantite());
                                $totalSortie = $totalSortie + $ligneRMensuel->getSortieClient();
                            }
                        }
                    } //fin sorties speciale 
                    //gestion des sorties damages  
                    if ($sortie->getIsDamage()) {
                        $ligneSorties = $sortie->getLigneSorties();
                        foreach ($ligneSorties as $ligneSortie) {
                            if ($ligneSortie->getProduitSite() == $produitSite) {
                                //affecter la qte entree 
                                $ligneRMensuel->setSortieDamage($ligneRMensuel->getSortieDamage() + $ligneSortie->getQuantite());
                                $totalSortie = $totalSortie + $ligneRMensuel->getSortieClient();
                            }
                        }
                    } //fin sortie damages 
                }
            } //fin sorties 

            /// ci dessous la variables qui va sommer les trasnferts  doit etre implementée

            if (isset($transfertsEntrant)) {
                // dd(gettype($transfertsEntrant).' et enfin sortant '.gettype($transfertsSortant));
                foreach ($transfertsEntrant as $transfert) {
                    //gestion des transferts entrants 
                    $siteCourant = $produitSite->getSite();
                    $ligneTransferts = $transfert->getLigneTransferts();
                    foreach ($ligneTransferts as $ligneTransfert) {
                        if ($ligneTransfert->getProduitSite()->getProduit() == $produitSite->getProduit()) {
                            // dd('Entrant entrant  le produit site ' . $produitSite . 'le produit de la ligne  ' . $ligneTransfert->getProduitSite());
                            //affecter la qte entree 
                            $ligneRMensuel->setQuantiteEntreeTransfert($ligneRMensuel->getQuantiteEntreeTransfert() + $ligneTransfert->getQuantite());
                        }
                        // dd('Entrant hors jeux le produit site ' . $produitSite->getPn() . 'le produit de la ligne  ' . $ligneTransfert->getProduitSite()->getPn());
                    }
                }
            } //fin transfert entrant  

            if (isset($transfertsSortant)) {
                foreach ($transfertsSortant as $transfert) {
                    //gestion des transferts entrants 
                    $siteCourant = $produitSite->getSite();
                    $ligneTransferts = $transfert->getLigneTransferts();
                    foreach ($ligneTransferts as $ligneTransfert) {
                        if ($ligneTransfert->getProduitSite() == $produitSite) {
                            // dd('Sortant sortant  le produit site ' . $produitSite . 'le produit de la ligne  ' . $ligneTransfert->getProduitSite());

                            //affecter la qte entree 
                            $ligneRMensuel->setQuantiteSortieTransfert($ligneRMensuel->getQuantiteSortieTransfert() + $ligneTransfert->getQuantite());
                        }
                    }
                }
            } //fin transfert sortant  

            $rMensuelSite->addLigneRMensuel($ligneRMensuel);
        }
        $entityManager->persist($rMensuelSite);
        $entityManager->flush();
        return $this->redirectToRoute('r_mensuel_site_index');
    }

    /**
     * @Route("/{id}", name="r_mensuel_site_show", methods={"GET"})
     */
    public function show(RMensuelSite $rMensuelSite): Response
    {
        return $this->render('r_mensuel_site/show.html.twig', [
            'r_mensuel_site' => $rMensuelSite,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="r_mensuel_site_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, RMensuelSite $rMensuelSite): Response
    {
        $form = $this->createForm(RMensuelSiteType::class, $rMensuelSite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('r_mensuel_site_index');
        }

        return $this->render('r_mensuel_site/edit.html.twig', [
            'r_mensuel_site' => $rMensuelSite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/clorurer", name="r_mensuel_site_clorurer", methods={"GET","POST"})
     */
    public function cloturer(Request $request, RMensuelSite $rMensuelSite): Response
    {
        if ($rMensuelSite->getIsCloture() == true) {
            $this->addFlash('warning', 'Rapport du mois de ' . $rMensuelSite->getDesignationMois() . '-' . $rMensuelSite->getAnnee() . ' a déjà été cloturé. ');

            return $this->redirectToRoute('r_mensuel_site_index');
        }

        //// seul les rapports dont tous les trasferts sont validé pourront etre cloturer
        $mois = $rMensuelSite->getMois();
        $annee = $rMensuelSite->getAnnee();
        $site = $this->getUser()->getSiteActif();
        $transfertEntrants = $this->getDoctrine()->getRepository(Transfert::class)->findBy(['mois' => $mois, 'annee' => $annee, 'isValidee' => false, 'siteReception' => $site]);
        if (sizeof($transfertEntrants) > 0) {
            $this->addFlash('warning', 'Vous ne pouvez pas clôturer un rapport dont certains transferts ENTRANT ne sont pas validé. ');
            return $this->redirectToRoute('r_mensuel_site_show', ['id' => $rMensuelSite->getId()]);
        }

        $transfertsSortants = $this->getDoctrine()->getRepository(Transfert::class)->findBy(['mois' => $mois, 'annee' => $annee, 'isValidee' => false, 'siteEnvoie' => $site]);
        if (sizeof($transfertsSortants) > 0) {
            $this->addFlash('warning', 'Vous ne pouvez pas clôturer un rapport dont certains transferts ENTRANT ne sont pas validé. ');
            return $this->redirectToRoute('r_mensuel_site_show', ['id' => $rMensuelSite->getId()]);
        }

        $rMensuelSite->setIsCloture(true);
        //instancier des verrou de ce mois 
        $verrouMouvement = new VerrouMouvement(new DateTime(), $site, $mois, $annee, $rMensuelSite);

        $this->getDoctrine()->getManager()->persist($verrouMouvement);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', 'Rapport du mois de ' . $rMensuelSite->getDesignationMois() . '-' . $rMensuelSite->getAnnee() . ' a été cloturé. ');

        return $this->redirectToRoute('r_mensuel_site_show', ['id' => $rMensuelSite->getId()]);
    }



    /**
     * @Route("/{id}/actualiser", name="r_mensuel_site_actualiser", methods={"GET","POST"})
     */
    public function actualiser(Request $request, RMensuelSite $rMensuelSite): Response
    {


        ///supprimer l'ancien rapport 
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($rMensuelSite);
        $entityManager->flush();
        $this->addFlash('success', 'Rapport du mois de ' . $rMensuelSite->getDesignationMois() . '-' . $rMensuelSite->getAnnee() . ' a été actualisé. ');
        return $this->redirectToRoute('r_mensuel_site_new');
    }

    /**
     * @Route("/{id}", name="r_mensuel_site_delete", methods={"DELETE"})
     */
    public function delete(Request $request, RMensuelSite $rMensuelSite): Response
    {
        if ($this->isCsrfTokenValid('delete' . $rMensuelSite->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($rMensuelSite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('r_mensuel_site_index');
    }
}
