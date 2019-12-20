<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login-user", name="security-login")
     * @param AuthenticationUtils $helper
     * AuthenticationUtils sert a afficher message d'erreur
     * @return Response
     */
    public function userLogin(AuthenticationUtils $helper){

        dump($helper->getLastAuthenticationError());

        return $this->render('security/login.html.twig',[
            'lastUserName' => $helper->getLastUsername(),
            'error' => $helper->getLastAuthenticationError(),
            'formTitle' => 'Identification des utilisateurs',
            'formAction' => $this->generateUrl('user-login-check')
        ]);
    }
}