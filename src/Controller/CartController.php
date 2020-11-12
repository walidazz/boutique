<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\CartService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class CartController extends AbstractController
{
    /**
     * @Route("/mon-panier", name="my_cart")
     */
    public function index(CartService $cart, ProductRepository $repo): Response
    {
        $cartComplete = $cart->index();
        return $this->render('cart/index.html.twig', ['cart' => $cartComplete]);
    }


    /**
     * @Route("/cart/add/{id}", name="add_to_cart")
     */
    public function add(CartService $cart, $id): Response
    {
        $cart->add($id);
        //  $this->addFlash('success', 'Article ajouté au panier');
        return $this->redirectToRoute('products');
    }




    /**
     * @Route("/cart/add/quantity/{id}", name="cart_add_quantity")
     */
    public function addQuantity(CartService $cart, $id): Response
    {
        $cart->add($id);
        return $this->redirectToRoute('my_cart');
    }



    /**
     * @Route("/cart/reduce/quantity/{id}", name="cart_reduce_quantity")
     */
    public function reduceQuantity(CartService $cart, $id): Response
    {
        $cart->reduce($id);
        return $this->redirectToRoute('my_cart');
    }

    /**
     * @Route("/cart/remove/{id}", name="remove_to_cart")
     */
    public function removeToCart(CartService $cart, $id): Response
    {
        $cart->removeproduct($id);
        return $this->redirectToRoute('my_cart');
    }




    /**
     * @Route("/cart/remove", name="remove_cart")
     */
    public function remove(CartService $cart): Response
    {
        $cart->remove();
        return $this->redirectToRoute('products');
    }
}
