<?php

namespace App\Controller;

use App\Entity\RapportAnnuelSite;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;

/**
 *@IsGranted("IS_AUTHENTICATED_FULLY")
 * @Route("/accueil")
 */
class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(): Response
    {
        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
        ]);
    }

    /**
     * @Route("/{id}", name="suppression_temporaire", methods={"DELETE", "GET"})
     */
    public function delete(Request $request, RapportAnnuelSite $rapportAnnuelSite): Response
    {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($rapportAnnuelSite);
            $entityManager->flush();
            $this->addFlash('success', 'Opération réussie avec succès.');


        return $this->redirectToRoute('rapport_annuel_site_index');
    }
}
