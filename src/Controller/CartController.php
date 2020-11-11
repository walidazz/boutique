<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\CartService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    /**
     * @Route("/mon-panier", name="my_cart")
     */
    public function index(CartService $cart, ProductRepository $repo): Response
    {
        // dd($cart->get());





        $cartComplete = [];


        foreach ($cart->get() as $id => $quantite) {
            $cartComplete[] = [
                'product' => $repo->find($id),
                'quantity' => $quantite,
                'price' => $repo->find($id)->getPrice(),
                'total' => $repo->find($id)->getPrice() * $quantite,

            ];
        }

        return $this->render('cart/index.html.twig', ['cart' => $cartComplete]);
    }


    /**
     * @Route("/cart/add/{id}", name="add_to_cart")
     */
    public function add(CartService $cart, $id): Response
    {
        $cart->add($id);
        $this->addFlash('success', 'Article ajouté au panier');
        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
        ]);
    }



    /**
     * @Route("/cart/remove", name="remove_cart")
     */
    public function remove(CartService $cart): Response
    {
        $cart->remove();
        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
        ]);
    }
}
