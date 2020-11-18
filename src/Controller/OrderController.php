<?php

namespace App\Controller;

use App\Entity\Order;
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


        return $this->render('order/index.html.twig', ['form' => $form->createView(), 'cart' => $cart->index()]);
    }



    /**
     * @Route("/order/recap", name="order_recap")
     */
    public function add(CartService $cart, Request $request, EntityManagerInterface $em): Response
    {


        $form =    $this->createForm(OrderType::class, null, ['user' => $this->getUser()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $carrier = $this->form->get('carriers')->getData();
            $delivery = $this->get('delivery')->getData();

            $order = new Order();
            $order->setUser($this->getUser());
            $order->setCreatedAt(new \DateTime('now'));
            $order->setCarrierName($carrier->getName())
                ->setCarrierPrice($carrier->getPrice())
                ->setDelivery($delivery);

            $em->persist($order);
            $em->flush();



            // TODO: Enregistrer la commande en base de donnée, à faire aujourd'hui !
        }



        return $this->render('order/index.html.twig', ['cart' => $cart->index()]);
    }
}
