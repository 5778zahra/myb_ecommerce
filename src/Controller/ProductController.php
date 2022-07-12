<?php

namespace App\Controller;

use App\Classe\Search;
use App\Entity\Product;
//use App\Form\SearchType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/produits', name: 'app_product')]
    public function index(Request $request, ProductRepository $productRepository): Response
    {
        $products = $this->entityManager->getRepository(Product::class)->findAll();

        $search = new Search();
        $form = $this->createForm(SearchType::class);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $products = $productRepository->findBy(['name' => $form->getData()], ['name' => 'ASC']);
            $products = $this->entityManager->getRepository(Product::class)->findWithSearch($search);
            //$products = $this->entityManager->getRepository(Product::class)->findWithSearch($search);
            //dd($products);
        }
        
        return $this->render('product/index.html.twig', [
            //'controller_name' => 'ProductController',
            'listProducts' => $products,
            'form' => $form->createView()
        ]);
    }
    
    #[Route('/produits/{slug}', name: 'show_product')]
    public function show($slug): Response
    {
        //dd($slug);
        $product = $this->entityManager->getRepository(Product::class)->findOneBySlug($slug);
        $products = $this->entityManager->getRepository(Product::class)->findByIsBest(1);

        //dd($products);
        if (!$product) {
            return $this->redirectToRoute('app_product');
        }

        
        return $this->render('product/show.html.twig', [
            'controller_name' => 'ProductController',
            'product' => $product,
            'products'=> $products
        ]);
    }
}
