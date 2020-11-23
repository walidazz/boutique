<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\Order;
use App\Form\OrderType;
use App\Entity\OrderDetails;
use App\Service\CartService;
use Stripe\Checkout\Session;
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
     * @Route("/order/recap", name="order_recap", methods={"POST"})
     */
    public function add(CartService $cart, Request $request, EntityManagerInterface $em): Response
    {

        $YOUR_DOMAIN = 'http://localhost:8000';
        Stripe::setApiKey('sk_test_51H1sWcLy7uMHC2RaylK0bOSgK842qsbW3guK8w0aMVlRYnsypDkiqGfcw0WcVRuX3oEWJFpsm9vcN3Fd1I96MqBa00S1LOaX0e');

        $form =    $this->createForm(OrderType::class, null, ['user' => $this->getUser()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $date = new \DateTime();
            $carriers = $form->get('carriers')->getData()[0];

            $delivery = $form->get('adresses')->getData()[0];


            $delivery_content = $delivery->getFirstname() . ' ' . $delivery->getLastname();
            $delivery_content .= '<br/>' . $delivery->getPhone();

            if ($delivery->getCompany()) {
                $delivery_content .= '<br/>' . $delivery->getCompany();
            }

            $delivery_content .= '<br/>' . $delivery->getAdress();
            $delivery_content .= '<br/>' . $delivery->getPostal() . ' ' . $delivery->getCity();
            $delivery_content .= '<br/>' . $delivery->getCountry();

            // dd($carriers);
            // Enregistrer ma commande Order()
            $order = new Order();
            //   $reference = $date->format('dmY') . '-' . uniqid();
            // $order->setReference($reference);
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setCarrierName($carriers->getName());
            $order->setCarrierPrice($carriers->getPrice());
            $order->setDelivery($delivery_content);
            $order->setIsPaid(0);
            $em->persist($order);

            $product_for_stripe = [];
            foreach ($cart->index() as $product) {

                $orderDetails = new OrderDetails();
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($product['product']->getName());
                $orderDetails->setPrice($product['product']->getPrice());
                $orderDetails->setQuantity($product['quantity']);
                $orderDetails->setTotal($product['product']->getPrice() * $product['quantity']);
                $em->persist($orderDetails);

                $product_for_stripe = [


                    'price_data' => [
                        'currency' => 'eur',
                        'unit_amount' => $product['product']->getPrice(),
                        'product_data' => [
                            'name' => $product['product']->getName(),
                            'images' => [$YOUR_DOMAIN . '/uploads/'  . $product['product']->getIllustration()],
                        ],
                    ],
                    'quantity' => $product['quantity'],

                ];
            }
            //$em->flush(); //TODO: à enlever apresy



            $checkout_session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    $product_for_stripe
                ],
                'mode' => 'payment',
                'success_url' => $YOUR_DOMAIN . '/success.html',
                'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
            ]);
            //dump($checkout_session->id);
            //dd($checkout_session);
            return $this->render('order/add.html.twig', ['cart' => $cart->index(), 'carrier' => $carriers, 'delivery' => $delivery_content]);
        }
        return $this->redirectToRoute('my_cart');
    }
}
