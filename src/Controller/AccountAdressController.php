<?php

namespace App\Controller;

use Twig\Environment;
use App\Entity\Adress;
use App\Form\AdressType;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AccountAdressController
{

    private Environment $twig;

    private EntityManagerInterface $manager;
    private CartService $cart;
    private RouterInterface $router;
    private FormFactoryInterface $form;
    private Security $security;



    public function __construct(Environment $twig, EntityManagerInterface $manager, CartService $cart, RouterInterface $router, FormFactoryInterface $form, Security $security)
    {
        $this->twig = $twig;
        $this->manager = $manager;
        $this->cart = $cart;
        $this->router = $router;
        $this->form = $form;
        $this->security = $security;
    }

    /**
     * @Route("/profile/adress", name="account_adress")
     */
    public function index(): Response
    {

        return new Response($this->twig->render('/account/account_adress.html.twig'));
    }

    /**
     * @Route("/profile/edit/adress/{id}", name="edit_adress")
     * @Route("/profile/add/adress", name="add_adress")
     */
    public function edit(Adress $adress = null, Request $request, Session $session): Response
    {
        if (!$adress) {
            $adress = new Adress();
            $create = true;
        } else {
            $create = false;
        }

        if (!$create && $adress->getUser() != $this->security->getUser()) {
            return  new RedirectResponse($this->router->generate('account'));
        }

        $form = $this->form->create(AdressType::class, $adress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $adress->setUser($this->security->getUser());
            $this->manager->persist($adress);
            $this->manager->flush();

            ($create) ?   $session->getFlashBag()->add('success', 'Adresse ajoutée avec succes !') :  $session->getFlashBag()->add('success', 'Adresse modifiée avec succes !');
            if ($this->cart->get()) {
                return new RedirectResponse($this->router->generate('my_order'));
            }
            return new RedirectResponse($this->router->generate('account_adress'));
        }

        return new Response($this->twig->render('/account/adress_form.html.twig', ['form' => $form->createView()]));
    }


    /**
     * @Route("/profile/delete/adress/{id}", name="delete_adress")
     */
    public function delete(Adress $adress, Session $session): Response
    {
        if ($adress->getUser() == $this->security->getUser()) {
            $this->manager->remove($adress);
            $this->manager->flush();
            $session->getFlashBag()->add('success', 'Suppression effectuée!');
        }
        return new Response($this->router->generate('account_adress'));
    }
}
