<?php

namespace App\Controller;

use App\Entity\Site;
use App\Entity\Client;
use App\Entity\LigneSortie;
use App\Entity\Sortie;
use App\Entity\ProduitSite;
use App\Form\SortieType;
use App\Form\SortieDamageType;
use App\Repository\ProduitSiteRepository;
use App\Repository\SortieRepository;
use App\Repository\ClientRepository;
use App\Repository\RMensuelSiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 *@IsGranted("IS_AUTHENTICATED_FULLY")
 * @Route("/sortie")
 */
class SortieController extends AbstractController
{

    /**
     * @Route("/creationDamage", name="creation_damage", methods={"GET"})
     */
    public function creationDamage(ProduitSiteRepository $produitSiteRepository): Response
    {
        $site = $this->getUser()->getSiteActif();
        $produitSites = $produitSiteRepository->findBy(['site' => $site]);
        //exit(var_dump($produitSites));
        if (sizeof($produitSites) == 0) {
            $this->addFlash('warning', 'Votre site n\'a reçu aucun produit en entrée. ');
        }
        return $this->render('sortie/creationDamage.html.twig', [
            'produits' => $produitSites,
        ]);
    }

    /**
     * @Route("/creationSortie", name="creation_sortie", methods={"GET"})
     */
    public function creationSortie(ClientRepository $clientRepository): Response
    {
        //seuls les site simple qui ne sont pas warehouse peuvent effectués les sorties 

        $siteActif = $this->getUser()->getSiteActif();
        if ($siteActif->getIsWarehouse() == true) {
            $this->addFlash('warning', 'Un warehouse ne peut livrer directement à un client. Connectez-vous comme site simple. ');

            return $this->redirectToRoute('sortie_index');
        }
        //se rassurer que le site ne manque pas de produits 
        $produitSiteRepository = $this->getDoctrine()->getRepository(ProduitSite::class);
        $produitSites = $produitSiteRepository->findBy(['site' => $siteActif]);

        if (sizeof($produitSites) == 0) {
            $this->addFlash('warning', 'Votre site ne contient aucun produit. ');

            return $this->redirectToRoute('sortie_index');
        }

        return $this->render('sortie/creationSortie.html.twig', [
            'clients' => $clientRepository->findBy(['isSite' => false], ['noms' => 'ASC']),
        ]);
    }

    /**
     * @Route("/", name="sortie_index", methods={"GET"})
     */
    public function index(SortieRepository $sortieRepository): Response
    {

        $site = $this->getUser()->getSiteActif();
        return $this->render('sortie/index.html.twig', [
            'sorties' => $sortieRepository->findBy(['siteEnvoie' => $site], ['date' => 'DESC']),
        ]);
    }

    /**
     * @Route("/indexDamage", name="sortie_damage_index", methods={"GET"})
     */
    public function indexDamage(SortieRepository $sortieRepository): Response
    {
        $site = $this->getUser()->getSiteActif();
        return $this->render('sortie/index.html.twig', [
            'sorties' => $sortieRepository->findBy(['siteEnvoie' => $site, 'isDamage' => true], ['date' => 'DESC']),
        ]);
    }

    /**
     * @Route("/indexSortieSpeciale", name="sortie_speciale_index", methods={"GET"})
     */
    public function indexSortieSpeciale(SortieRepository $sortieRepository): Response
    {
        $site = $this->getUser()->getSiteActif();
        return $this->render('sortie/index.html.twig', [
            'sorties' => $sortieRepository->findBy(['siteEnvoie' => $site, 'isSortieSpeciale' => true]),
        ]);
    }

    /**
     * @Route("/{id}/new", name="sortie_new", methods={"GET","POST"})
     */
    public function new(Request $request, Client $client, RMensuelSiteRepository $repo): Response
    {
        //seuls les site simple qui ne sont pas warehouse peuvent effectués les sorties 

        $siteActif = $this->getUser()->getSiteActif();
        if ($siteActif->getIsWarehouse() == true) {
            $this->addFlash('warning', 'Un warehouse ne peut livrer directement à un client. Connectez-vous comme site simple. ');

            return $this->redirectToRoute('sortie_index');
        }

        //verifier s'il existe au moin un site et un produits dans le site 
        $sites = $this->getDoctrine()->getManager()->getRepository(Site::class)->findAll();
        $produitSites = $this->getDoctrine()->getManager()->getRepository(ProduitSite::class)->findAll();

        if (!$sites) {
            $this->addFlash('warning', 'Aucun site dans la base. Enregistrer d\'abord un site . ');

            return $this->redirectToRoute('sortie_index');
        }
        if (!$produitSites) {
            $this->addFlash('warning', 'Aucun produit dans votre site, demandez le réapprovisionnement. ');

            return $this->redirectToRoute('sortie_index');
        }

        $sortie = new Sortie($client);
        $sortie->setUser($this->getUser());
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //verifier que ce mois n'est pas verrouiller 
            $date=$sortie->getDate();
            $mois = $date->format('m');
            $annee = $date->format('Y');
            $verouMouvement = $this->checkVerrou($mois,$annee,$repo);
            if($verouMouvement){
                $this->addFlash('info','Ce mois est déjà verouiller');
                return $this->redirectToRoute('accueil');
            }
            ////////////

            //affecter le user et le site emetteur 
            $user = $this->getUser();
            $site = $user->getSiteActif();
            $sortie->setUser($user);
            $sortie->setSiteEnvoie($site);
            //verifier que la sortie comprend des lignes sorties 
            $ligneSorties = $sortie->getLigneSorties();
            if (sizeof($ligneSorties) == 0) {
                $this->addFlash('warning', 'Vous ne pouvez enregistré une sortie sans détail.');

                return $this->redirectToRoute('sortie_index');
            }
            //verifier que les doublons sur les lignes 
            // $tableau = array();
            // foreach ($ligneSorties as $ligneSortie1) {
            //    $tableau[] = $ligneSortie1->getLigneSortieTransfert()->getProduitSite()->getId();
            // }
            // $array_unique=array_unique($tableau);

            //if($array_unique!=$tableau){
            //    $this->addFlash('warning', 'Vous ne pouvez enregistré deux fois le même produit.');

            //   return $this->redirectToRoute('sortie_index');           
            //}


            //verifier que les quantités sorties sont inferieur à celle en stock 
            $entityManager = $this->getDoctrine()->getManager();

            $quantiteProduit = 0;
            foreach ($ligneSorties as $ligneSortie) {
                $produitSite = $ligneSortie->getProduitSite();
                $produit = $produitSite->getProduit();
                $quantiteProduit =  $produitSite->getQuantite();
                $quantiteSortie = $ligneSortie->getQuantite();
                if ($quantiteProduit < $quantiteSortie) {
                    $this->addFlash('warning', 'La quantité du produit ' . $produitSite . ' est inférieur à la sortie.');

                    return $this->redirectToRoute('sortie_index');
                }
                $produitSite->setQuantite($quantiteProduit - $quantiteSortie);
                $produit->setQuantite($produit->getQuantite() - $quantiteSortie);

                $entityManager->persist($produit);
                $entityManager->persist($produitSite);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sortie);
            $entityManager->flush();
            $this->addFlash('success', 'Opération réussie avec succès.');

            return $this->redirectToRoute('sortie_index');
        }

        return $this->render('sortie/new.html.twig', [
            'sortie' => $sortie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sortie_show", methods={"GET"})
     */
    public function show(Sortie $sortie): Response
    {
        return $this->render('sortie/show.html.twig', [
            'sortie' => $sortie,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="sortie_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Sortie $sortie): Response
    {
        $form = $this->createForm(SortieType::class, $sortie, [
            'action' => $this->generateUrl('sortie_new', ['id' => $sortie->getClient()->getId()]),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('sortie_index');
        }
        ///
        $entityManager = $this->getDoctrine()->getManager();
        //mettre a jour le produit site 
        $ligneSorties = $sortie->getLigneSorties();
        foreach ($ligneSorties as $ligneSortie) {
            $produitSite = $ligneSortie->getProduitSite();
            $produit = $produitSite->getProduit();
            $quantiteProduitSite =  $produitSite->getQuantite();
            $quantiteSortie = $ligneSortie->getQuantite();
            if ($quantiteProduitSite < $quantiteSortie) {
                $this->addFlash('warning', 'La quantité du produit ' . $produitSite . ' est inférieur à la sortie.');

                return $this->redirectToRoute('sortie_index');
            }
            $produitSite->setQuantite($quantiteProduitSite + $quantiteSortie);
            $produit->setQuantite($produit->getQuantite() + $quantiteSortie);

            $entityManager->persist($produit);
            $entityManager->persist($produitSite);
        }
        $entityManager->remove($sortie);
        $entityManager->flush();
        $this->addFlash('warning', 'la sortie a été temporairement supprimé. Veuillez confirmer après modification pour rétablissement ');


        return $this->render('sortie/edit.html.twig', [
            'sortie' => $sortie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sortie_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Sortie $sortie): Response
    {
        if ($this->isCsrfTokenValid('delete' . $sortie->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            //mettre a jour le produit site 
            $ligneSorties = $sortie->getLigneSorties();
            foreach ($ligneSorties as $ligneSortie) {
                $produitSite = $ligneSortie->getProduitSite();
                $produit = $produitSite->getProduit();
                $quantiteProduitSite =  $produitSite->getQuantite();
                $quantiteSortie = $ligneSortie->getQuantite();
                if ($quantiteProduitSite < $quantiteSortie) {
                    $this->addFlash('warning', 'La quantité du produit ' . $produitSite . ' est inférieur à la sortie.');

                    return $this->redirectToRoute('sortie_index');
                }
                $produitSite->setQuantite($quantiteProduitSite + $quantiteSortie);
                $produit->setQuantite($produit->getQuantite() + $quantiteSortie);

                $entityManager->persist($produit);
                $entityManager->persist($produitSite);
            }
            $entityManager->remove($sortie);
            $entityManager->flush();
            $this->addFlash('success', 'Opération réussie. ');
        }

        return $this->redirectToRoute('sortie_index');
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
