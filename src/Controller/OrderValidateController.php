<?php

namespace App\Controller;

use App\Entity\Order;
use App\Service\CartService;
use App\Service\MailService;
use App\Service\SwiftMailerService;
use Doctrine\ORM\EntityManagerInterface;
use Swift_Mailer;
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
    public function index($stripeSessionId, CartService $cartService, \Swift_Mailer $mailer): Response
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

            $message = $this->createMessage($order);

            $mailer->send($message);
            $this->addFlash('succes', 'mail envoyé correctement');
            //     $mail->send($order->getUser(), 'Votre commande a bien été validé !', 'Merci pour votre commande !');
        }


        return $this->render('order_validate/index.html.twig', [
            'order' => $order,
        ]);
    }

    /**
     * Undocumented function
     *
     * @param Order $order
     */
    private function createMessage(Order $order)
    {
        $message = (new \Swift_Message('Facture n° ' . $order->getReference()))

            ->setFrom('hisokath12@gmail.com')
            ->setTo($order->getUser()->getEmail())
            ->setBody(
                $this->renderView(
                    'invoice/_orderInvoice.html.twig',
                    [
                        'order' => $order,
                    ]
                ),
                'text/html'
            );

        return $message;
    }
}
