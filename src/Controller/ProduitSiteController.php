<?php

namespace App\Controller;

use App\Entity\ProduitSite;
use App\Form\ProduitSiteType;
use App\Repository\ProduitSiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/produitle/site")
 */
class ProduitSiteController extends AbstractController
{
    /**
     * @Route("/", name="produit_site_index", methods={"GET"})
     */
    public function index(ProduitSiteRepository $produitSiteRepository): Response
    {
        $site=$this->getUser()->getSiteActif();
        $produitSites = $produitSiteRepository->findBy(['site'=>$site]);
        //exit(var_dump($produitSites));
        if (sizeof($produitSites) == 0) {
            $this->addFlash('warning', 'Votre site n\'a reçu aucun produit en entrée. ');
        } 
        return $this->render('produit_site/index.html.twig', [
            'produits' => $produitSites,
        ]);
    
    }

        /**
     * @Route("/produitAutreSite", name="produit_autre_site", methods={"GET"})
     */
    public function produitAutreSite(ProduitSiteRepository $produitSiteRepository): Response
    {
        $site=$this->getUser()->getSiteActif();
        $produitSites = $produitSiteRepository->findAll();
        //exit(var_dump($produitSites));
        if (sizeof($produitSites) == 0) {
            $this->addFlash('warning', 'Votre site n\'a reçu aucun produit en entrée. ');
        } 
        return $this->render('produit_site/indexAutresSites.html.twig', [
            'produits' => $produitSites,
        ]);
    
    }

        /**
     * @Route("/produitSiteAlerte", name="produit_site_alerte_index", methods={"GET"})
     */
    public function produitSiteAlerte(ProduitSiteRepository $produitSiteRepository): Response
    {
        $site=$this->getUser()->getSiteActif();
        $produitSites = $produitSiteRepository->findBy(['site'=>$site, 'isDangerStock'=>true]);
        $produitSitesTous = $produitSiteRepository->findBy(['site'=>$site]);
        if (sizeof($produitSites) == 0) {
            $this->addFlash('warning', 'Vous ne possedez pas de produit en stock. ');
            return $this->redirectToRoute('produit_site_index');

        } 
        //exit(var_dump($produitSites));
        if (sizeof($produitSites) == 0) {
            $this->addFlash('warning', 'Votre stock d\'alerte de vos produits est bon. ');
            return $this->redirectToRoute('produit_site_index');

        } 
        return $this->render('produit_site/index.html.twig', [
            'produits' => $produitSites,
        ]);
    
    }

    /**
     * @Route("/new", name="produit_site_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $produitSite = new ProduitSite();
        $form = $this->createForm(ProduitSiteType::class, $produitSite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($produitSite);
            $entityManager->flush();

            return $this->redirectToRoute('produit_site_index');
        }

        return $this->render('produit_site/new.html.twig', [
            'produit_site' => $produitSite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="produit_site_show", methods={"GET"})
     */
    public function show(ProduitSite $produitSite): Response
    {
        return $this->render('produit_site/show.html.twig', [
            'produit_site' => $produitSite,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="produit_site_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ProduitSite $produitSite): Response
    {
        $form = $this->createForm(ProduitSiteType::class, $produitSite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('produit_site_index');
        }

        return $this->render('produit_site/edit.html.twig', [
            'produit_site' => $produitSite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="produit_site_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ProduitSite $produitSite): Response
    {
        if ($this->isCsrfTokenValid('delete' . $produitSite->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($produitSite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('produit_site_index');
    }
}
