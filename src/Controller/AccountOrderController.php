<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountOrderController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    /**
     * @Route("/profile/order", name="account_order")
     */
    public function index(): Response
    {

        $orders =  $this->em->getRepository(Order::class)->findSuccessOrders($this->getUser());
        return $this->render('account/order.html.twig', [
            'orders' => $orders,
        ]);
    }


    /**
     * @Route("/profile/order/show/{reference}", name="account_order_show")
     */
    public function show($reference): Response
    {

        $order = $this->em->getRepository(Order::class)->findOneByReference($reference);

        if (!$order) {
            return $this->redirectToRoute('account_order');
        }
        return $this->render('account/order_show.html.twig', [
            'order' => $order,
        ]);
    }
}
