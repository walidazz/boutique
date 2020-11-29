<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Service\MailService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * @Route("/nous-contacter", name="contact")
     */
    public function index(Request $requestVar, MailService $mail): Response
    {

        $form = $this->createForm(ContactType::class);


        $form->handleRequest($requestVar);

        if ($form->isSubmitted() && $form->isValid()) {


            $this->addFlash('success', "Merci de nous avoir contacter, nous vous recontacterons dans les plus bréfs délais ");

            //TODO: mettre en place l'envoie d'email pour les mails de contact 


            return $this->redirectToRoute('homepage');
        }


        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
