<?php

namespace App\Controller;

use App\Form\OrderType;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    /**
     * @Route("/order", name="my_order")
     */
    public function index(CartService $cart, Request $request, EntityManagerInterface $em): Response
    {

        if (!$this->getUser()->getAdresses()->getValues()) {
            return $this->redirectToRoute('add_adress');
        }
        $form =    $this->createForm(OrderType::class, null, ['user' => $this->getUser()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // TODO: Enregistrer la commande en base de donnée, à faire 
        }



        return $this->render('order/index.html.twig', ['form' => $form->createView(), 'cart' => $cart->index()]);
    }
}
