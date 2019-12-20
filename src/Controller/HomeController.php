<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dodoma")
 * Class HomeController
 * @package App\Controller
 */
class HomeController extends AbstractController
{

    /**
     * @Route("/home", name="homepage")
     * @return Response
     */
    public function index(){
        return $this->render("home/index.html.twig", ["message"=>"Hello Symfony"]);
    }


}