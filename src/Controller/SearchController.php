<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    /**
     * @Route("/search/", name="search", methods={"GET"})
     */
    public function search(Request $request, ProductRepository $repo, PaginatorInterface $paginator)
    {

        $search = $request->query->get('q');

        $products = $paginator->paginate(
            $repo->search($search),
            $request->query->getInt('page', 1),
            9
        );
        return $this->render('product/searchpage.html.twig', compact('products'));
    }
}
