<?php

namespace App\Controller;

use App\Entity\LigneRAnnuelGeneral;
use App\Entity\Produit;
use App\Entity\RAnnuelGeneral;
use App\Entity\RapportAnnuelSite;
use App\Form\RAnnuelGeneralType;
use App\Form\RGeneralType;
use App\Repository\RAnnuelGeneralRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/rannuelgeneral")
 */
class RAnnuelGeneralController extends AbstractController
{
    /**
     * @Route("/", name="r_annuel_general_index", methods={"GET"})
     */
    public function index(RAnnuelGeneralRepository $rAnnuelGeneralRepository): Response
    {

        return $this->render('r_annuel_general/index.html.twig', [
            'r_annuel_generals' => $rAnnuelGeneralRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="r_annuel_general_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {

        $site = $this->getUser()->getSiteActif();

        $produits = $this->getDoctrine()->getRepository(Produit::class)->findAll();


        $annee = 1;


        ////
        $rapportGenerals = $this->getDoctrine()->getRepository(RAnnuelGeneral::class)->findBy([]);
        if (sizeof($rapportGenerals) > 0) {
            ///trouver rapport le plus petit 
            $tab = [];
            foreach ($rapportGenerals as $rapportGeneral) {
                $tab[] = $rapportGeneral->getAnnee();
            }
            $anneeMax = max($tab);
            $annee = $anneeMax + 1;
            ///instancier un rapports generale 
            $rapportGeneral = new RAnnuelGeneral();
            $rapportGeneral->setAnnee($annee);
        } else {
            $this->addFlash('info', 'Ceci est votre tout premier rapport général.');

            ////verifier qu'il existe un rapport annuel passe , si oui le recupere sinon commencer le premier rapport annuel 
            $rapportAnnuelSites = $this->getDoctrine()->getRepository(RapportAnnuelSite::class)->findBy([]);
            if (sizeof($rapportAnnuelSites) == 0) {
                $this->addFlash('warning', 'Aucun rapport annuel de site dans le système. ');
                return $this->redirectToRoute('accueil');
            }
            ///verifier que tout les rannuel site sont cloturé 
            foreach ($rapportAnnuelSites as $rapportAnnuelSite) {
                if ($rapportAnnuelSite->getIsCloture() == false) {
                    $this->addFlash('warning', 'Le rapport ' . $rapportAnnuelSite . ' du site ' . $rapportAnnuelSite->getSite() . ' est non cloturé .');
                    return $this->redirectToRoute('accueil');
                }
            }
            ///initialiser la premiere annee 
            $tab = [];
            foreach ($rapportAnnuelSites as $rapportAnnuelSite) {
                $tab[] = $rapportAnnuelSite->getAnnee();
            }
            $anneeMin = min($tab);
            $annee = $anneeMin;

            ///instancier un rapports generale 
            $rapportGeneral = new RAnnuelGeneral();
            $rapportGeneral->setAnnee($annee);
        }
        ///ici doit figurer le code de lecture des rapport annuel site car la valeur annee est deja initialisée 

        ////recalculer le rapport annuel site avec le parametre ou filtre année

        $rapportAnnuelSites = $this->getDoctrine()->getRepository(RapportAnnuelSite::class)->findBy(['annee' => $annee]);

        foreach ($produits as $produit) {
            $ligneRAnnuelGeneral = new LigneRAnnuelGeneral();
            $ligneRAnnuelGeneral->setPn($produit);
            foreach ($rapportAnnuelSites as $rapportAnnuelSite) {
                foreach ($rapportAnnuelSite->getLigneRAnnuelSites() as $ligne) {
                    if ($ligne->getPn()->getProduit() == $produit) {
                        $ligneRAnnuelGeneral->setQteInitiale($ligneRAnnuelGeneral->getQteInitiale() + $ligne->getQuantiteInitiale());
                        $ligneRAnnuelGeneral->setQteEntreeFournisseur($ligneRAnnuelGeneral->getQteEntreeFournisseur() + $ligne->getQuantiteEntree());
                        $ligneRAnnuelGeneral->setQteEntreeReuse($ligneRAnnuelGeneral->getQteEntreeReuse() + $ligne->getQuantiteEntreeReuse());
                        $ligneRAnnuelGeneral->setQteSortieClient($ligneRAnnuelGeneral->getQteSortieClient() + $ligne->getSortieClient());
                        $ligneRAnnuelGeneral->setQteSortieDamage($ligneRAnnuelGeneral->getQteSortieDamage() + $ligne->getSortieDamage());
                        $ligneRAnnuelGeneral->setQteEntreeSpeciale($ligneRAnnuelGeneral->getQteEntreeSpeciale() + $ligne->getSortieDamage());
                        $ligneRAnnuelGeneral->setQteSortieSpeciale($ligneRAnnuelGeneral->getQteSortieSpeciale() + $ligne->getSortieDamage());
                        $ligneRAnnuelGeneral->setQteSolde($ligneRAnnuelGeneral->getQteSolde() + $ligne->getQuantiteFinale());
                    }
                }
            }
            $rapportGeneral->addLigneRAnnuelGeneral($ligneRAnnuelGeneral);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($rapportGeneral);
        $entityManager->flush();

        return $this->redirectToRoute('r_annuel_general_index');
    }

    /**
     * @Route("/{id}", name="r_annuel_general_show", methods={"GET"})
     */
    public function show(RAnnuelGeneral $rAnnuelGeneral): Response
    {
        return $this->render('r_annuel_general/show.html.twig', [
            'r_annuel_general' => $rAnnuelGeneral,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="r_annuel_general_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, RAnnuelGeneral $rAnnuelGeneral): Response
    {
        $form = $this->createForm(RAnnuelGeneralType::class, $rAnnuelGeneral);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('r_annuel_general_index');
        }

        return $this->render('r_annuel_general/edit.html.twig', [
            'r_annuel_general' => $rAnnuelGeneral,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/deletion/vite", name="r_annuel_general_delete", methods={"DELETE", "GET"})
     */
    public function delete(Request $request, RAnnuelGeneral $rAnnuelGeneral): Response
    {
        // if ($this->isCsrfTokenValid('delete' . $rAnnuelGeneral->getId(), $request->request->get('_token'))) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($rAnnuelGeneral);
        $entityManager->flush();
        // }
        $this->addFlash('info', 'Suppression réussie .');

        return $this->redirectToRoute('r_annuel_general_index');
    }
}
