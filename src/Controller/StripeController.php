<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\Order;
use App\Entity\Product;
use App\Service\CartService;
use Stripe\Checkout\Session;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{


    /**
     * @Route("/order/create-session/{reference}", name="stripe_create_session")
     */
    public function index(CartService $cart, $reference, EntityManagerInterface $em): Response
    {

        $YOUR_DOMAIN = 'http://localhost:8000';
        Stripe::setApiKey('sk_test_51H1sWcLy7uMHC2RaylK0bOSgK842qsbW3guK8w0aMVlRYnsypDkiqGfcw0WcVRuX3oEWJFpsm9vcN3Fd1I96MqBa00S1LOaX0e');
        $product_for_stripe = [];

        /** @var Order */
        $order = $em->getRepository(Order::class)->findOneByReference($reference);
        if (!$order) {

            return new JsonResponse(['error' => 'order']);
        }


        if (!$order) {
            new JsonResponse(['error' => 'order']);
        }

        foreach ($order->getOrderDetails()->getValues() as $product) {
            $product_object = $em->getRepository(Product::class)->findOneByName($product->getProduct());
            $products_for_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $product->getPrice(),
                    'product_data' => [
                        'name' => $product->getProduct(),
                        'images' => [$YOUR_DOMAIN . "/uploads/" . $product_object->getIllustration()],
                    ],
                ],
                'quantity' => $product->getQuantity(),
            ];
        }


        $products_for_stripe[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $order->getCarrierPrice(),
                'product_data' => [
                    'name' => 'Transport: ' . $order->getCarrierName(),
                    'images' => [$YOUR_DOMAIN],
                ],
            ],
            'quantity' => 1,
        ];

        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => [
                $products_for_stripe
            ],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/order/success/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/order/error/{CHECKOUT_SESSION_ID}',
        ]);

        $order->setStripeSessionId($checkout_session->id);
        $em->flush();

        $response = new JsonResponse(['id' => $checkout_session->id]);

        return $response;
    }
}
