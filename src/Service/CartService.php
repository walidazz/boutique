<?php

namespace App\Service;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{

    private $session;
    private $repo;

    public function __construct(SessionInterface $session, ProductRepository $repo)
    {
        $this->session = $session;
        $this->repo = $repo;
    }




    public function add($id)
    {

        $cart = $this->session->get('cart', []);

        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $this->session->set('cart', $cart);
    }


    public function reduce($id)
    {
        $cart = $this->session->get('cart', []);
        if ($cart[$id] > 1) {
            $cart[$id]--;
        } else {
            unset($cart[$id]);
        }

        $this->session->set('cart', $cart);
    }


    public function removeproduct($id)
    {
        $cart = $this->session->get('cart');

        if (!empty($cart[$id])) {
            unset($cart[$id]);
        }

        $this->session->set('cart', $cart);

        //  $this->session->set('cart', $cart);

    }



    public function get()
    {
        return    $this->session->get('cart');
    }

    public function remove()
    {
        return  $this->session->remove('cart');
    }

    public function index()
    {
        $cartComplete = [];
        if (!empty($this->get())) {


            foreach ($this->get() as $id => $quantite) {

                $product = $this->repo->findOneBy(['id' => $id]);
                if (!$product) {
                    $this->removeproduct($id);
                    continue;
                }
                $cartComplete[] = [
                    'product' => $this->repo->find($product),
                    'quantity' => $quantite,
                    'price' => $this->repo->find($product)->getPrice(),
                    'total' => $this->repo->find($product)->getPrice() * $quantite,

                ];
            }
        }
        return $cartComplete;
    }
}
