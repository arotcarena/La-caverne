<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ProductFilter;
use App\Form\ProductFilterType;
use App\Repository\ProductRepository;
use App\Vico\UrlHelper;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Vico\ImagePagination;

class ProductController extends AbstractController
{
    #[Route('/nos-produits', name: 'product_index')]
    public function index(ProductRepository $productRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $productRepository->findAllQuery();
        
        $productFilter = new ProductFilter();
        $form = $this->createForm(ProductFilterType::class, $productFilter);

        $form->handleRequest($request);
        if($form->isSubmitted() AND $form->isValid())
        {
            $query = $productRepository->findFilteredQuery($productFilter);
        }
        $pagination = $paginator->paginate(
        $query,
        $request->query->getInt('page', 1),
        8
        ); 

        return $this->renderForm('view/product/index.html.twig', [
            'current_menu' => 'product',
            'pagination' => $pagination,
            'form' => $form
        ]);
    }

    #[Route('/{slug}/ref-{id}', name: 'product_show')]
    public function show(Product $product, string $slug): Response
    {
        if($slug !== $product->getSlug())
        {
            return $this->redirectToRoute('product_show', ['id' => $product->getId(), 'slug' => $product->getSlug()]);
        }

        $imgPagination = new ImagePagination($product, new UrlHelper());

        return $this->render('view/product/show.html.twig', [
            'current_menu' => 'product',
            'product' => $product,
            'imgPagination' => $imgPagination
        ]);
    }
}
