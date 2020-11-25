<?php

namespace App\Controller;

use App\Entity\Order;
use App\Service\CartService;
use App\Service\MailService;
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
    public function index($stripeSessionId, CartService $cartService, MailService $mail): Response
    {
        /** @var Order */
        $order =      $this->em->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);
        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('homepage');
        }

        if ($order->getState() === 0) {
            $order->setState(1);
            $this->em->flush();
            $cartService->remove();
            $mail->send($order->getUser(), 'Votre commande a bien été validé !', 'Merci pour votre commande !');

        }


        return $this->render('order_validate/index.html.twig', [
            'order' => $order,
        ]);
    }
}
