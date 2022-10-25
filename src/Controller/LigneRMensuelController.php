<?php

namespace App\Controller;

use App\Entity\LigneRMensuel;
use App\Form\LigneRMensuel1Type;
use App\Repository\LigneRMensuelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ligne/r/mensuel")
 */
class LigneRMensuelController extends AbstractController
{
    /**
     * @Route("/", name="ligne_r_mensuel_index", methods={"GET"})
     */
    public function index(LigneRMensuelRepository $ligneRMensuelRepository): Response
    {
        return $this->render('ligne_r_mensuel/index.html.twig', [
            'ligne_r_mensuels' => $ligneRMensuelRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="ligne_r_mensuel_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $ligneRMensuel = new LigneRMensuel();
        $form = $this->createForm(LigneRMensuel1Type::class, $ligneRMensuel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ligneRMensuel);
            $entityManager->flush();

            return $this->redirectToRoute('ligne_r_mensuel_index');
        }

        return $this->render('ligne_r_mensuel/new.html.twig', [
            'ligne_r_mensuel' => $ligneRMensuel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ligne_r_mensuel_show", methods={"GET"})
     */
    public function show(LigneRMensuel $ligneRMensuel): Response
    {
        return $this->render('ligne_r_mensuel/show.html.twig', [
            'ligne_r_mensuel' => $ligneRMensuel,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="ligne_r_mensuel_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, LigneRMensuel $ligneRMensuel): Response
    {
        $form = $this->createForm(LigneRMensuel1Type::class, $ligneRMensuel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ligne_r_mensuel_index');
        }

        return $this->render('ligne_r_mensuel/edit.html.twig', [
            'ligne_r_mensuel' => $ligneRMensuel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ligne_r_mensuel_delete", methods={"DELETE"})
     */
    public function delete(Request $request, LigneRMensuel $ligneRMensuel): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ligneRMensuel->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ligneRMensuel);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ligne_r_mensuel_index');
    }
}
