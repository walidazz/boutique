<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class AccountController
{

    private $em;
    private $router;
    private $twig;

    public function __construct(EntityManagerInterface $em, Environment $twig, RouterInterface $router)
    {
        $this->em = $em;
        $this->twig = $twig;
        $this->router = $router;
    }

    /**
     * @Route("/profile", name="account")
     */
    public function index(): Response
    {
        return new Response($this->twig->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
        ]));
    }


    /**
     * @Route("/profile/edit/{id}", name="profile_edit")
     * @IsGranted("ROLE_USER")
     */
    public function profile(User $user, Request $request, FormFactoryInterface $formfactory, Session $session)
    {
        $form = $formfactory->create(UserProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($user);
            $this->em->flush();
            $session->getFlashBag()->add('success', 'Modification effectué avec succès !');
            return new RedirectResponse($this->router->generate('account'));
        }

        return new Response($this->twig->render('account/profileType.html.twig', [
            'form' => $form->createView(),
        ]));
    }
}
