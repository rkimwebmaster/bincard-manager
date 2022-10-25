<?php

namespace App\Controller;

use App\Entity\LigneSortie;
use App\Entity\ProduitSite;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Form\SortieDamageType;
use App\Repository\RMensuelSiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 *@IsGranted("IS_AUTHENTICATED_FULLY")
 * @Route("/damage")
 */
class DamageController extends AbstractController
{
    /**
     * @Route("/damage", name="damage")
     */
    public function index(): Response
    {
        return $this->render('damage/index.html.twig', [
            'controller_name' => 'DamageController',
        ]);
    }


    /**
     * @Route("/{id}/nouveauDamage", name="nouveau_damage")
     */
    public function nouveauDamage(ProduitSite $produitSite, Request $request,RMensuelSiteRepository $repo)
    {

        //seuls les site simple qui ne sont pas warehouse peuvent effectués les sorties 

        $siteActif = $this->getUser()->getSiteActif();

        //verifier s'il existe au moin un site et un produits dans le site 
        $sites = $this->getDoctrine()->getManager()->getRepository(Site::class)->findAll();
        if (!$sites) {
            $this->addFlash('warning', 'Aucun site dans la base. Enregistrer d\'abord un site . ');

            return $this->redirectToRoute('sortie_index');
        }
        $sortie = new Sortie();
        $sortie->setUser($this->getUser());
        $sortie->setIsDamage(true);
        $ligneSortie = new LigneSortie();
        $ligneSortie->setProduitSite($produitSite);
        $sortie->addLigneSorty($ligneSortie);
        $form = $this->createForm(SortieDamageType::class, $sortie);
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
                $produit=$produitSite->getProduit();

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
            $this->addFlash('success', 'Operation réussie.');

            return $this->redirectToRoute('sortie_index');
        }

        return $this->render('sortie/newDamage.html.twig', [
            'sortie' => $sortie,
            'form' => $form->createView(),
        ]);
    }

        /**
     * @Route("/{id}/editDamage", name="sortie_damage_edit", methods={"GET","POST"})
     */
    public function editDamage(Request $request, Sortie $sortie, SessionInterface $sessionInterface): Response
    {
        // $ligneSorties=$sortie->getLigneSorties();
        // $uniqueLigneSortie=$ligneSorties[0];
        // $produit=$uniqueLigneSortie->getProduitSite();

        $oldLigneSortie=new ArrayCollection();

        $oldLigneSortie= $sortie->getLigneSorties()[0];
        if($sessionInterface->get('ancienneValeur')==null){
            $sessionInterface->set('ancienneValeur',$oldLigneSortie);

        }else{
            $sessionInterface->get('ancienneValeur');

        }
        $form = $this->createForm(SortieDamageType::class, $sortie, [
            // 'action' => $this->generateUrl('nouveau_damage', ['id' => $produit->getId()]),
            // 'method' => 'POST',
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // dd($sessionInterface->get('ancienneValeur'));
            // ici on doit maintenant recuperer la valeur tel 

            $ancienneValeur=$sessionInterface->get('ancienneValeur');
            // dd($ancienneValeur);
            // dd($oldLigneSortie[0]->getQuantite());

            if($ancienneValeur !== $sortie->getLigneSorties()[0]){
                $ancienneQuantite=$ancienneValeur->getQuantite();
                $ligneSortie=$sortie->getLigneSorties()[0];
                $quantite=$ligneSortie->getQuantite();
                $produitSite=$ligneSortie->getProduitSite();
                $produit=$produitSite->getProduit();
                $produit->setQuantite($produit->getQuantite()+$ancienneQuantite-$quantite);
                // dd($quantite);
                $produitSite->setQuantite($produitSite->getQuantite()+$ancienneQuantite-$quantite);
                $sessionInterface->remove('ancienneValeur');
                // dd('bamba sasa ..... ');
                // dd($produitSite->getQuantite());
            }
        // dd($oldLigneSortie[0]);
        // dd($sortie->getLigneSorties()[0]);
        // $sessionInterface->session_destroy;


        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        $this->addFlash('success', 'Vous avez modifier le damage. ');
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('sortie_index');
        }
        
        return $this->render('sortie/newDamage.html.twig', [
            'sortie' => $sortie,
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
