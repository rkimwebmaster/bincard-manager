<?php

namespace App\Controller;

use App\Entity\Site;
use App\Repository\SiteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
* @Route("/")
*/
class SecurityController extends AbstractController
{
    /**
     * @Route("/", name="app_login")
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
           if ($this->getUser()) {
            $this->addFlash('success', 'Vous êtes connécté déjà. ');

               return $this->redirectToRoute('accueil');
           }
        //    $username=$request->request->get('username')->getData();
        //    dd($username);
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

        /**
     * @Route("/{id}/siteActif", name="site_actif", methods={"GET","POST"})
     */
    public function siteActif(Request $request, Site $site): Response
    {
        $user=$this->getUser();
        $user->setSiteActif($site);
        //exit(var_dump($user->getSiteActif()));
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('search_billcard');

    }

        /**
     * @Route("/choixSite", name="choix_site", methods={"GET"})
     */
    public function choixSite(SiteRepository $siteRepository): Response
    {
        $siteGeres=$this->getUser()->getSiteGeres();
        //exit(var_dump(gettype($siteGeres)));

        return $this->render('security/choixSite.html.twig', [
            //'sites' => $siteRepository->findAll(),
            'sites' => $siteGeres,
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        $this->addFlash('success', 'Déconnexion réussie. ');

        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
