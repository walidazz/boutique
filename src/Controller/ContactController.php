<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * @Route("/nous-contacter", name="contact")
     */
    public function index(Request $requestVar, EntityManagerInterface $em): Response
    {

        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);


        $form->handleRequest($requestVar);

        if ($form->isSubmitted() && $form->isValid()) {


            $em->persist($contact);
            $em->flush();
            $this->addFlash('success', "Merci de nous avoir contacter, nous vous recontacterons dans les plus bréfs délais ");
            return $this->redirectToRoute('homepage');
        }


        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
