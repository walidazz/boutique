<?php

namespace App\Controller;

use App\Entity\Order;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderValidateController extends AbstractController
{

    private $em;


    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }



    /**
     * @Route("/order/success/{stripeSessionId}", name="order_success")
     */
    public function index($stripeSessionId, CartService $cartService): Response
    {
        /** @var Order */
        $order =      $this->em->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);
        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('homepage');
        }

        if (!$order->getIsPaid()) {
            $order->setIsPaid(1);
            $this->em->flush();
            $cartService->remove();
        }


        return $this->render('order_validate/index.html.twig', [
            'order' => $order,
        ]);
    }
}
