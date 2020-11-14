<?php

namespace App\Controller;

use App\Form\OrderType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @Route("/order", name="my_order")
     */
    public function index(): Response
    {

        if (!$this->getUser()->getAdresses()->getValues()) {
            return $this->redirectToRoute('add_adress');
        }
        $form =    $this->createForm(OrderType::class, null, ['user' => $this->getUser()]);
        return $this->render('order/index.html.twig', ['form' => $form->createView()]);
    }
}
