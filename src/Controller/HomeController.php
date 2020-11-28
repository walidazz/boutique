<?php

namespace App\Controller;

use App\Entity\Header;
use App\Entity\Product;
use App\Service\MailService;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    /**
     * @Route("/", name="homepage")
     */
    public function index(): Response
    {

        $products =    $this->em->getRepository(Product::class)->findByIsBest(1);
        $headers = $this->em->getRepository(Header::class)->findAll();
        return $this->render('home/index.html.twig', compact('products', 'headers'));
    }
}
