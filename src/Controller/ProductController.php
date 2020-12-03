<?php

namespace App\Controller;

use App\Entity\Search;
use App\Entity\Product;
use App\Form\SearchType;
use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController
{
    /**
     * @Route("/nos-produits", name="products")
     */
    public function index(ProductRepository $repo, Request $request, PaginatorInterface $paginator): Response
    {
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $products = $paginator->paginate(
                $repo->findWithSearch($search),
                $request->query->getInt('page', 1),
                9
            );
        } else {
            $products = $paginator->paginate(
                $repo->findAll(),
                $request->query->getInt('page', 1),
                9
            );
        }

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'form' => $form->createView()
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
