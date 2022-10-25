<?php

namespace App\Controller;

use App\Entity\LigneBillcard;
use App\Form\LigneBillcardType;
use App\Repository\LigneBillcardRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ligne/billcard")
 */
class LigneBillcardController extends AbstractController
{


    /**
     * @Route("/", name="ligne_billcard_index", methods={"GET"})
     */
    public function index(LigneBillcardRepository $ligneBillcardRepository): Response
    {
        $siteActif = $this->getUser()->getSiteActif();
        return $this->render('ligne_billcard/index.html.twig', [
            'ligne_billcards' => $ligneBillcardRepository->findBy(['site' => $siteActif], ['createdAt' => 'DESC',]),
        ]);
    }

    /**
     * @Route("/{id}", name="ligne_billcard_show", methods={"GET"})
     */
    public function show(LigneBillcard $ligneBillcard): Response
    {
        return $this->render('ligne_billcard/show.html.twig', [
            'ligne_billcard' => $ligneBillcard,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="ligne_billcard_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, LigneBillcard $ligneBillcard): Response
    {
        $form = $this->createForm(LigneBillcardType::class, $ligneBillcard);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ligne_billcard_index');
        }

        return $this->render('ligne_billcard/edit.html.twig', [
            'ligne_billcard' => $ligneBillcard,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ligne_billcard_delete", methods={"DELETE"})
     */
    public function delete(Request $request, LigneBillcard $ligneBillcard): Response
    {
        if ($this->isCsrfTokenValid('delete' . $ligneBillcard->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ligneBillcard);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ligne_billcard_index');
    }
}
