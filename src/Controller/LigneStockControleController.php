<?php

namespace App\Controller;

use App\Entity\LigneStockControle;
use App\Entity\Produit;
use App\Entity\ProduitSite;
use App\Form\LigneStockControleType;
use App\Form\SearchBillcardType;
use App\Repository\LigneStockControleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
*@IsGranted("IS_AUTHENTICATED_FULLY")
 * @Route("/ligneBincard")
 */
class LigneStockControleController extends AbstractController
{


        /**
     * @Route("/searchByPN", name="serachByPN", methods={"GET","POST"})
     */
    public function searchByPN(Request $request, LigneStockControleRepository $ligneStockControleRepository): Response
    {

        $pn = $request->get('pn');
        //dd($pn);
        $mouvement='tous mouvement';
        $produit=$this->getDoctrine()->getRepository(Produit::class)->findOneBy(['pn'=>$pn]);
        if($produit==null){
            $this->addFlash('warning', 'Vous avez entrer un mauvais PN. ');
            return $this->redirectToRoute('search_billcard');
        }
        $produitSite=$this->getDoctrine()->getRepository(ProduitSite::class)->findOneBy(['produit'=>$produit]);
        $ligneBillCards=$ligneStockControleRepository->findBy(['produitSite'=>$produitSite]);
        return $this->render('ligne_stock_controle/index.html.twig', [
            'ligne_billcards' => $ligneBillCards,
            'mouvement' => $mouvement,
            // 'dateDebut' => $dateDebut,
            'produitSite' => $produitSite,
            // 'produitSite' => $produitSite,
            // 'produitSite' => $produitSite,


        ]);
    }
    /**
     * @Route("/searchBillcard", name="search_billcard", methods={"GET","POST"})
     */
    public function searchBillcard(Request $request, LigneStockControleRepository $ligneStockControleRepository): Response
    {
        $site = $this->getUser()->getSiteActif();
        $form = $this->createForm(SearchBillcardType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mouvement = $form->get('mouvement')->getData();
            $produitSite = $form->get('produitSite')->getData();
            $dateDebut = $form->get('dateDebut')->getData();
            $dateFin = $form->get('dateFin')->getData();

            //dd($mouvement);

            //dd(empty($mouvement));

            if( isset($mouvement) &&  isset($produitSite) && isset($dateDebut) &&  isset($dateFin)){
                //dd('france');
                $ligneBillCards = $ligneStockControleRepository->findBySearch($site, $produitSite, $mouvement, $dateDebut, $dateFin);
            } elseif(isset($mouvement) &&  isset($produitSite) && null==$dateDebut &&  null==$dateFin){
                $ligneBillCards = $ligneStockControleRepository->findByProduitMouvement($site, $produitSite, $mouvement, $dateDebut, $dateFin);
            }elseif(null==$mouvement &&  isset($produitSite) && null==$dateDebut &&  null==$dateFin){
                $ligneBillCards = $ligneStockControleRepository->findByProduit($site, $produitSite, $mouvement, $dateDebut, $dateFin);
            }
            // $ligneBillCards = $ligneBillcardRepository->findBySearch($site, $produitSite,$mouvement);
            if (sizeof($ligneBillCards) == 0) {
                $this->addFlash('warning', 'Aucune donnée trouvée. ');

                return $this->redirectToRoute('search_billcard');
            }
            return $this->render('ligne_stock_controle/index.html.twig', [
                'ligne_billcards' => $ligneBillCards,
                'mouvement' => $mouvement,
                // 'dateDebut' => $dateDebut,
                'produitSite' => $produitSite,
                // 'produitSite' => $produitSite,
                // 'produitSite' => $produitSite,


            ]);
        }

        return $this->render('ligne_stock_controle/searchBillcard.html.twig', [
            //            'client' => $client,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/", name="ligne_stock_controle_index", methods={"GET"})
     */
    public function index(LigneStockControleRepository $ligneStockControleRepository): Response
    {
        $siteActif = $this->getUser()->getSiteActif();
        return $this->render('ligne_stock_controle/index.html.twig', [
            'ligne_billcards' => $ligneStockControleRepository->findBy(['site' => $siteActif], ['createdAt' => 'ASC',]),
        ]);
    }

    

    /**
     * @Route("/{id}", name="ligne_stock_controle_show", methods={"GET"})
     */
    public function show(LigneStockControle $ligneStockControle): Response
    {
        return $this->render('ligne_stock_controle/show.html.twig', [
            'ligne_stock_controle' => $ligneStockControle,
        ]);
    }



}
