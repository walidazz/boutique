<?php

namespace App\Controller;

use App\Entity\Order;
use Twig\Environment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountOrderController
{

    private $em;
    private $twig;
    private $router;
    private $security;

    public function __construct(EntityManagerInterface $em, Environment $twig, RouterInterface $router, Security $security)
    {
        $this->em = $em;
        $this->twig = $twig;
        $this->router = $router;
        $this->security = $security;
    }


    /**
     * @Route("/profile/order", name="account_order")
     */
    public function index(): Response
    {

        $orders =  $this->em->getRepository(Order::class)->findSuccessOrders($this->security->getUser());
        return new Response($this->twig->render('account/order.html.twig', [
            'orders' => $orders,
        ]));
    }


    /**
     * @Route("/profile/order/show/{reference}", name="account_order_show")
     */
    public function show($reference): Response
    {

        $order = $this->em->getRepository(Order::class)->findOneByReference($reference);

        if (!$order) {
            return new RedirectResponse($this->router->generate('account_order'));
        }
        return new Response($this->twig->render('account/order_show.html.twig', [
            'order' => $order,
        ]));
    }
}
