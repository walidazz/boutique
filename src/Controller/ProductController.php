<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/nos-produits", name="products")
     */
    public function index(ProductRepository $repo): Response
    {

        return $this->render('product/index.html.twig', [
            'products' => $repo->findAll(),
        ]);
    }

    /**
     * @Route("/produit/{slug}", name="product_show")
     */
    public function show(Product $product): Response
    {

        return $this->render('product/product_detail.html.twig', [
            'product' => $product,
        ]);
    }
}
