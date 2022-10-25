<?php

namespace App\Controller;

use App\Entity\Controle;
use App\Entity\Entree;
use App\Entity\LigneEntree;
use App\Entity\LigneSortie;
use App\Entity\Sortie;
use App\Form\ControleType;
use App\Repository\ControleRepository;
use App\Repository\RMensuelSiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 *@IsGranted("IS_AUTHENTICATED_FULLY")
 * @Route("/controle")
 */
class ControleController extends AbstractController
{

    /**
     * @Route("/", name="controle_index", methods={"GET"})
     */
    public function index(ControleRepository $controleRepository): Response
    {
        $siteActif = $this->getUser()->getSiteActif();
        return $this->render('controle/index.html.twig', [
            'controles' => $controleRepository->findBy(['site' => $siteActif], ['date' => 'DESC']),
        ]);
    }

    /**
     * @Route("/new", name="controle_new", methods={"GET","POST"})
     */
    public function new(Request $request, RMensuelSiteRepository $repo): Response
    {
        $user = $this->getUser();
        $site = $user->getSiteActif();
        $controle = new Controle();
        $controle->setSite($site);
        $controle->setUser($user);
        $form = $this->createForm(ControleType::class, $controle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //verifier que ce mois n'est pas verrouiller 
            $date=$controle->getDate();
            $mois = $date->format('m');
            $annee = $date->format('Y');
            $verouMouvement = $this->checkVerrou($mois,$annee,$repo);
            if($verouMouvement){
                $this->addFlash('info','Ce mois est déjà verouiller');
                return $this->redirectToRoute('sortie_index');
            }
            ////////////

            $ligneControles = $controle->getLigneControles();
            //verifier que pas de ligne vide 
            if (count($ligneControles) == 0) {
                $this->addFlash('danger', 'Vous devez ajouter au moin un détail.');

                return $this->redirectToRoute('controle_new');
            }

            //   verifier que les doublons sur les lignes 
            $tableau = array();
            foreach ($ligneControles as $ligne1) {
                $tableau[] = $ligne1->getProduitSite()->getId();
            }
            $array_unique = array_unique($tableau);

            if ($array_unique != $tableau) {
                $this->addFlash('danger', 'Vous ne pouvez controler/noter deux fois le même produit.');

                return $this->redirectToRoute('controle_index');
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($controle);
            $entityManager->flush();
            $this->addFlash('success', 'Opération réussie. ');

            return $this->redirectToRoute('controle_index');
        }

        return $this->render('controle/new.html.twig', [
            'controle' => $controle,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="controle_show", methods={"GET"})
     */
    public function show(Controle $controle): Response
    {
        return $this->render('controle/show.html.twig', [
            'controle' => $controle,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="controle_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Controle $controle): Response
    {
        $controle->getLigneControles()[0]->setSurplus(0);
        $controle->getLigneControles()[0]->setManquant(0);
        // $ligneControle;
        $form = $this->createForm(ControleType::class, $controle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //   verifier que les doublons sur les lignes 
            $ligneControles = $controle->getLigneControles();
            $tableau = array();
            foreach ($ligneControles as $ligne1) {
                $tableau[] = $ligne1->getProduitSite()->getId();
            }
            $array_unique = array_unique($tableau);

            if ($array_unique != $tableau) {
                $this->addFlash('danger', 'Vous ne pouvez controler/noter deux fois le même produit.');

                return $this->redirectToRoute('controle_index');
            }

            //fin ckeck doublon 
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Opération réussie. ');

            return $this->redirectToRoute('controle_index');
        }

        return $this->render('controle/edit.html.twig', [
            'controle' => $controle,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("valider/{id}/valider", name="controle_valider", methods={"GET","POST"})
     */
    public function valider(Request $request, Controle $controle): Response
    {
            //    exit(var_dump('teston'));
        $user = $this->getUser();
        $siteActif = $user->getSiteActif();

        $controle->setIsValidee(true);
        // exit(var_dump('jambo'));
        $entityManager = $this->getDoctrine()->getManager();

        $ligneControles = $controle->getLigneControles();
        foreach ($ligneControles as $ligne) {
            $produitSite = $ligne->getProduitSite();
            $produit = $produitSite->getProduit();
            $manquant = $ligne->getManquant();
            $surplus = $ligne->getSurplus();
            $ligne->updateLigneBincard();

            if ($ligne->checkManquantSurplus() == -1) {
                // dd('cas de manquant pour créer sortie speciale  ');
                //créer sortie speciale 
                $sortieSpeciale = new Sortie(null);
                $sortieSpeciale->setIsSortieSpeciale(true);
                $sortieSpeciale->setUser($user);
                $sortieSpeciale->setSiteEnvoie($siteActif);
                $sortieSpeciale->setDate($controle->getDate('- 1 day'));
                $ligneSortie = new LigneSortie();
                $ligneSortie->setProduitSite($produitSite);
                $ligneSortie->setQuantite($manquant);
                $sortieSpeciale->addLigneSorty($ligneSortie);

                $ligneSortie->updateLigneBincard();
                ///mise a jour pour affceter le ijd qui est le code du controle 
                // dd($controle->getCode());
                $ligneSortie->getLigneStockControle()->setIjdNumber($controle->getCode());
                // dd($ligneSortie->getLigneStockControle()->getIjdNumber());
                //mettre a jour la qté produit site 
                $produitSite->setQuantite($produitSite->getQuantite()-$manquant);
                $entityManager->persist($produitSite);

                $entityManager->persist($sortieSpeciale);
                // dd($ligneSortie->getLigneStockControle()->getIjdNumber());

                $entityManager->persist($ligneSortie->getLigneStockControle());
                // dd($ligneSortie);
            } elseif ($ligne->checkManquantSurplus() == 1) {
                //créer entree speciale 
                $entreeSpeciale = new Entree(null, $siteActif);
                $entreeSpeciale->setDate($controle->getDate('- 1 day'));
                $entreeSpeciale->setIsEntreeSpeciale(true);
                $entreeSpeciale->setUser($user);
                $ligneEntree = new LigneEntree();
                $ligneEntree->setProduitSite($produitSite);
                $ligneEntree->setProduit($produit);
                $ligneEntree->setQuantite($surplus);
                $entreeSpeciale->addLigneEntree($ligneEntree);
                $ligneEntree->updateLigneBillcard();
                ///mise a jour pour affceter le ijd qui est le code du controle 
                $ligneEntree->getLigneStockControle()->setIjdNumber($controle->getCode());
                //mettre a jour produit site 
                $produitSite->setQuantite($produitSite->getQuantite()+$surplus);
                $entityManager->persist($produitSite);
                $entityManager->persist($entreeSpeciale);
                // dd($ligneEntree);
            }
        }
        

        // dd($controle->getLigneControles()[0]->getLigneStockControle());

        $entityManager->persist($controle);

        $entityManager->flush();
        // dd('dehors');

        $this->addFlash('success', 'Opération réussie. ');

        return $this->redirectToRoute('controle_index');
    }

    /**
     * @Route("/{id}", name="controle_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Controle $controle): Response
    {
        if ($this->isCsrfTokenValid('delete' . $controle->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($controle);
            $entityManager->flush();
            $this->addFlash('success', 'Opération réussie. ');
        }

        return $this->redirectToRoute('controle_index');
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
