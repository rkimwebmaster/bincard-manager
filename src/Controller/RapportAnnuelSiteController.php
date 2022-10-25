<?php

namespace App\Controller;

use App\Entity\LigneRAnnuelSite;
use App\Entity\RapportAnnuelSite;
use App\Entity\RMensuelSite;
use App\Entity\ProduitSite;
use App\Form\RapportAnnuelSiteType;
use App\Form\RASiteType;
use App\Repository\RapportAnnuelSiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/rapportannuelsite")
 */
class RapportAnnuelSiteController extends AbstractController
{
    /**
     * @Route("/", name="rapport_annuel_site_index", methods={"GET"})
     */
    public function index(RapportAnnuelSiteRepository $rapportAnnuelSiteRepository): Response
    {
        $site = $this->getUser()->getSiteActif();
        $rapports = $rapportAnnuelSiteRepository->findBy(['site' => $site]);
        return $this->render('rapport_annuel_site/index.html.twig', [
            'rapport_annuel_sites' => $rapports,
        ]);
    }

    /**
     * @Route("/new", name="rapport_annuel_site_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {

        $site = $this->getUser()->getSiteActif();

        ////verifier qu'il existe un rapport annuel passe , si oui le recupere sinon commencer le premier rapport annuel 
        $rapportAnnuelPasses = $this->getDoctrine()->getRepository(RapportAnnuelSite::class)->findBy(['site' => $site]);

        // if(sizeof($rapportAnnuelPasses))
        if (sizeof($rapportAnnuelPasses) > 0) {

            $tab = [];
            foreach ($rapportAnnuelPasses as $rapportAnnuel) {
                $tab[] = $rapportAnnuel->getAnnee();
            }
            $annee = max($tab);
            $rapportPasse = $this->getDoctrine()->getRepository(RapportAnnuelSite::class)->findOneBy(['annee' => $annee, 'site' => $site]);
            //si le rapport passe est non cloturer , quitter 
            if ($rapportPasse->getIsCloture() == false) {
                $this->addFlash('warning', 'Le rapport ' . $rapportPasse->getAnnee() . ' est non cloturé. ');
                return $this->redirectToRoute('accueil');
            }
            // $anneeRapport=$petitRapport->getAnnee();
            $annee = $annee + 1;

            $rapportMensuels = $this->getDoctrine()->getRepository(RMensuelSite::class)->findBy(['site' => $site,'annee'=>$annee]);
            if (sizeof($rapportMensuels) == 0) {
                $this->addFlash('warning', 'Aucun rapport mensuel dans le systeme. ');
                return $this->redirectToRoute('accueil');
            }
        } else {
            //comme pas de rapport passe , initialiser l'année en onction des rapports mensuels 
            $rapportMensuels = $this->getDoctrine()->getRepository(RMensuelSite::class)->findBy(['site' => $site]);
            //trouver le rapport mensuel avec la plus petite annee qui va initialiser l'année
            $annees=[];
            foreach($rapportMensuels as $rapportMensuel){
                $annees[]=$rapportMensuel->getAnnee();

            }
            $annee = min($annees);

            $rapportMensuels = $this->getDoctrine()->getRepository(RMensuelSite::class)->findBy(['site' => $site,'annee'=>$annee]);
            if (sizeof($rapportMensuels) == 0) {
                $this->addFlash('warning', 'Aucun rapport mensuel dans le systeme. ');
                return $this->redirectToRoute('accueil');
            }
            ///si aucun rapport annuel site existe 

            ///lire les rapports mensuels et recuperer le plus anciennes années 
            ///verifier que tous les rapports mensuels sont cloturé 
            foreach ($rapportMensuels as $rapportMensuel) {
                if ($rapportMensuel->getIsCloture() == false) {
                    $this->addFlash('warning', 'Le rapport ' . $rapportMensuel . ' est non cloturé. ');
                    return $this->redirectToRoute('accueil');
                }
            }
            //le tableau des id pour comparer le min 
            $tab = [];
            foreach ($rapportMensuels as $rapportMensuel) {
                $tab[] = $rapportMensuel->getAnnee();
            }
            $annee = min($tab);
            $rapportPasse = null;
            $this->addFlash('info', 'Ce rapport est le tout premier. ');
        }

        $rapportAnnuelSite = new RapportAnnuelSite();
        $rapportAnnuelSite->setAnnee($annee);
        $rapportAnnuelSite->setSite($site);
        //lire tous les produits site du site en cours 
        $produitSites = $this->getDoctrine()->getRepository(ProduitSite::class)->findBy(['site' => $site]);
        //parcour      s des produits sites 
        $entityManager = $this->getDoctrine()->getManager();
        foreach ($produitSites as $produitSite) {
            $ligneRAnnuelSite = new LigneRAnnuelSite();
            //si existe un ancien rapport recuperer la qte initiale 
            if ($rapportPasse) {
                $ligneRapportPasses = $rapportPasse->getLigneRAnnuelSites();
                foreach ($ligneRapportPasses as $ligne) {
                    if ($ligne->getPn() == $produitSite) {
                        $ligneRAnnuelSite->setQuantiteInitiale($ligneRAnnuelSite->getQuantiteInitiale() + $ligne->getQuantiteFinale());
                    }
                }
            } else {
                $ligneRAnnuelSite->setQuantiteInitiale(0);
            }
            foreach ($rapportMensuels as $rapportMensuel) {
                $ligneRapportMensuels = $rapportMensuel->getLigneRMensuels();
                foreach ($ligneRapportMensuels as $ligneRapportMensuel) {
                    //se rassurer que le produit site correspond a celui de la boule des produits site 
                    if ($produitSite == $ligneRapportMensuel->getPn()) {

                        //recuperation des valeurs 
                        $quantiteEntree = $ligneRapportMensuel->getQuantiteEntree();
                        $quantiteEntreeReuse = $ligneRapportMensuel->getQuantiteEntreeReuse();
                        $quantiteEntreeSpeciale = $ligneRapportMensuel->getQuantiteEntreeSpeciale();
                        $quantiteEntreeTransfert = $ligneRapportMensuel->getQuantiteEntreeTransfert();
                        $quantiteSortieTransfert = $ligneRapportMensuel->getQuantiteSortieTransfert();
                        $sortieClient = $ligneRapportMensuel->getSortieClient();
                        $sortieSpeciale = $ligneRapportMensuel->getSortieSpeciale();
                        $sortieDamage = $ligneRapportMensuel->getSortieDamage();
                        // $quantiteFinale = $ligneRapportMensuel->getQuantiteFinale();
                        //affectation des valeurs 
                        $ligneRAnnuelSite->setQuantiteEntree($ligneRAnnuelSite->getQuantiteEntree() + $quantiteEntree);
                        $ligneRAnnuelSite->setQuantiteEntreeReuse($ligneRAnnuelSite->getQuantiteEntreeReuse() + $quantiteEntreeReuse);
                        $ligneRAnnuelSite->setQuantiteEntreeSpeciale($ligneRAnnuelSite->getQuantiteEntreeSpeciale() + $quantiteEntreeSpeciale);
                        $ligneRAnnuelSite->setQuantiteEntreeTransfert($ligneRAnnuelSite->getQuantiteEntreeTransfert() + $quantiteEntreeTransfert);
                        $ligneRAnnuelSite->setQuantiteSortieTransfert($ligneRAnnuelSite->getQuantiteSortieTransfert() + $quantiteSortieTransfert);
                        $ligneRAnnuelSite->setSortieClient($ligneRAnnuelSite->getSortieClient() + $sortieClient);
                        $ligneRAnnuelSite->setSortieSpeciale($ligneRAnnuelSite->getSortieSpeciale() + $sortieSpeciale);
                        $ligneRAnnuelSite->setSortieDamage($ligneRAnnuelSite->getSortieDamage() + $sortieDamage);
                        $ligneRAnnuelSite->setPn($ligneRapportMensuel->getPn());
                    }
                }
                ////ligne ici aupravent 
            }
            $rapportAnnuelSite->addLigneRAnnuelSite($ligneRAnnuelSite);
        }

        $entityManager->persist($rapportAnnuelSite);
        $entityManager->flush();
        $this->addFlash('success', 'Votre rapport annuel ' . $annee . ' a été généré ');
        return $this->redirectToRoute('rapport_annuel_site_show', ['id' => $rapportAnnuelSite->getId()]);
    }


    /**
     * @Route("/{id}/actualiser", name="r_annuel_site_actualiser", methods={"GET","POST"})
     */
    public function actualiser(Request $request,  RapportAnnuelSite $rapportAnnuelSite): Response
    {
        ///supprimer l'ancien rapport 
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($rapportAnnuelSite);
        $entityManager->flush();
        $this->addFlash('success', 'Rapport de ' . $rapportAnnuelSite->getAnnee() . ' a été actualisé. ');
        return $this->redirectToRoute('rapport_annuel_site_new');
    }

    /**
     * @Route("/{id}", name="rapport_annuel_site_show", methods={"GET"})
     */
    public function show(RapportAnnuelSite $rapportAnnuelSite): Response
    {
        return $this->render('rapport_annuel_site/show.html.twig', [
            'rapport_annuel_site' => $rapportAnnuelSite,
        ]);
    }

    /**
     * @Route("/{id}/cloturer", name="rapport_annuel_site_cloturer", methods={"GET","POST"})
     */
    public function cloturer(Request $request, RapportAnnuelSite $rapportAnnuelSite): Response
    {
        if ($rapportAnnuelSite->getIsCloture()) {
            $this->addFlash('warning', 'Rapport ' . $rapportAnnuelSite . ' a déjà été cloturé. ');
            return $this->redirectToRoute('rapport_annuel_site_index');
        } else {
            $this->addFlash('success', 'Rapport ' . $rapportAnnuelSite->getAnnee() . ' est cloturé avec succès. ');

            /////////////////////debut ici 

            //////////////////fin ici 
        }
        $entityManager = $this->getDoctrine()->getManager();
        $rapportAnnuelSite->setIsCloture(true);
        $entityManager->persist($rapportAnnuelSite);
        $entityManager->flush();
        $this->addFlash('success', 'Rapport ' . $rapportAnnuelSite . ' a été cloturé. ');

        // return $this->redirectToRoute('rapport_annuel_site_new',['val'=>'cloturer']);
        return $this->redirectToRoute('rapport_annuel_site_index');
    }

    /**
     * @Route("/{id}/edit", name="rapport_annuel_site_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, RapportAnnuelSite $rapportAnnuelSite): Response
    {
        $form = $this->createForm(RapportAnnuelSiteType::class, $rapportAnnuelSite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('rapport_annuel_site_index');
        }

        return $this->render('rapport_annuel_site/edit.html.twig', [
            'rapport_annuel_site' => $rapportAnnuelSite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="rapport_annuel_site_delete", methods={"DELETE"})
     */
    public function delete(Request $request, RapportAnnuelSite $rapportAnnuelSite): Response
    {
        if ($this->isCsrfTokenValid('delete' . $rapportAnnuelSite->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($rapportAnnuelSite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('rapport_annuel_site_index');
    }
}
