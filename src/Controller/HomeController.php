<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\MailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }


    /**
     * @Route("/email", name="email")
     */
    public function email(MailService $mail, UserRepository $repo): Response
    {
        

        $walid = $repo->findOneByEmail('walidazzimani@gmail.com');
        $mail->send($walid, 'test', 'voici le contenu');

        return $this->render('home/index.html.twig');
    }
}
