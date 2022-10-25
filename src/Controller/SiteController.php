<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\ProduitSite;
use App\Entity\Site;
use App\Entity\Transfert;
use App\Entity\Entree;
use App\Entity\Ville;
use App\Form\SiteType;
use App\Repository\SiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/site")
 */
class SiteController extends AbstractController
{
    /**
     * @Route("/", name="site_index", methods={"GET"})
     */
    public function index(SiteRepository $siteRepository): Response
    {
        return $this->render('site/index.html.twig', [
            'sites' => $siteRepository->findAll(),
        ]);
    }

    /**
     * @Route("/", name="warehouse_index", methods={"GET"})
     */
    public function indexWarehouse(SiteRepository $siteRepository): Response
    {
        return $this->render('site/index.html.twig', [
            'sites' => $siteRepository->findBy(['isWarehouse' => true]),
        ]);
    }

    /**
     * @Route("/new", name="site_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        //verifier si il existe au moins une ville 
        $ville = $this->getDoctrine()->getRepository(Ville::class)->findAll();
        if (sizeof($ville) == 0) {
            $this->addFlash('warning', 'Aucune ville dans la base de données.');

            return $this->redirectToRoute('site_index');
        }

        $site = new Site();
        $form = $this->createForm(SiteType::class, $site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($site);
            $entityManager->flush();
            $this->addFlash('success', 'Opération réussie. ');


            return $this->redirectToRoute('site_index');
        }

        return $this->render('site/new.html.twig', [
            'site' => $site,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="site_show", methods={"GET"})
     */
    public function show(Site $site): Response
    {
        return $this->render('site/show.html.twig', [
            'site' => $site,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="site_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Site $site): Response
    {
        $form = $this->createForm(SiteType::class, $site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Opération réussie. ');

            return $this->redirectToRoute('site_index');
        }

        return $this->render('site/edit.html.twig', [
            'site' => $site,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="site_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Site $site): Response
    {
        if ($this->isCsrfTokenValid('delete' . $site->getId(), $request->request->get('_token'))) {
            //verifier que le site est deja utilisé par des produits site  
            $produitSites = $this->getDoctrine()->getRepository(ProduitSite::class)->findBy(['site' => $site]);

            if (sizeof($produitSites) > 0) {

                $this->addFlash('warning', 'Ce site contient des produits en son sein. ');
                return $this->redirectToRoute('site_index');
            }

            //verifier que le site est deja utilisé par des transferts   
            $transferts1 = $this->getDoctrine()->getRepository(Transfert::class)->findBy(['siteEnvoie' => $site]);
            $transferts2 = $this->getDoctrine()->getRepository(Transfert::class)->findBy(['siteReception' => $site]);
            $transferts=array_merge($transferts1, $transferts2);
            if (sizeof($transferts) > 0) {

                $this->addFlash('warning', 'Ce site contient des transferts en son sein. ');
                return $this->redirectToRoute('site_index');
            }

            //verifier que le site est deja utilisé par des transferts   
            $sorties = $this->getDoctrine()->getRepository(Transfert::class)->findBy(['siteEnvoie' => $site]);
            if (sizeof($sorties) > 0) {

                $this->addFlash('warning', 'Ce site a déjà éffectué des sorties. ');
                return $this->redirectToRoute('site_index');
            }

            //verifier que le site est deja utilisé par des transferts   
            $entrees = $this->getDoctrine()->getRepository(Entree::class)->findBy(['siteReception' => $site]);
            if (sizeof($entrees) > 0) {

                $this->addFlash('warning', 'Ce site a déjà éffectué des entrées. ');
                return $this->redirectToRoute('site_index');
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($site);
            $entityManager->flush();
            $this->addFlash('success', 'Opération réussie. ');
        }

        return $this->redirectToRoute('site_index');
    }
}
