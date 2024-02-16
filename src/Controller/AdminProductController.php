<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Entity\Product;
use App\Vico\Form\Form;
use App\Form\ProductType;
use Vico\Form\ProductForm;
use App\Repository\BrandRepository;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Vico\Attachment\ProductAttachment;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/produits')]
class AdminProductController extends AbstractController
{
    #[Route('/', name: 'admin_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $pagination = $paginator->paginate(
            $productRepository->findAllQuery(),
            $request->query->getInt('page', 1),
            10
        ); 


        return $this->render('view/admin/product/index.html.twig', [
            'current_menu' => 'admin_product',
            'pagination' => $pagination
        ]);
    }

    /**
     * PAS D UPLOAD D IMAGES POSSIBLE
     */
    #[Route('/new', name: 'admin_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProductRepository $productRepository): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productRepository->add($product);
            return $this->redirectToRoute('admin_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('view/admin/product/new.html.twig', [
            'product' => $product,
            'form' => $form,
            'current_menu' => 'admin_product'
        ]);
    }



    /**
     * DANS CETTE FONCTION J AI FAIT LE FORM MOI MEME POUR FAIRE L UPLOAD DES IMAGES SANS BUNDLE 
     */
    #[Route('/{id}/edit', name: 'admin_product_edit', methods: ['GET', 'POST'])]
    public function edit(Product $product, ProductRepository $productRepository, CategoryRepository $categoryRepository, BrandRepository $brandRepository): Response
    {
        $form = new ProductForm($categoryRepository, $brandRepository, $product);
        $form->handleRequest(array_merge($_POST, $_FILES));

        if ($form->isSubmitted() && $form->isValid()) 
        {
            ProductAttachment::updateImages($product);
            $productRepository->add($product);
            return $this->redirectToRoute('admin_product_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('view/admin/product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
            'current_menu' => 'admin_product'
        ]);
    }

    #[Route('/{id}', name: 'admin_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $productRepository->remove($product);
        }

        return $this->redirectToRoute('admin_product_index', [], Response::HTTP_SEE_OTHER);
    }
}
