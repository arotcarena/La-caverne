<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Form\BrandType;
use App\Repository\BrandRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/marques')]
class AdminBrandController extends AbstractController
{
    #[Route('/', name: 'admin_brand_index', methods: ['GET'])]
    public function index(BrandRepository $brandRepository): Response
    {
        return $this->render('view/admin/brand/index.html.twig', [
            'brands' => $brandRepository->findAll(),
            'current_menu' => 'admin_brand'
        ]);
    }

    #[Route('/new', name: 'admin_brand_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BrandRepository $brandRepository): Response
    {
        $brand = new Brand();
        $form = $this->createForm(BrandType::class, $brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $brandRepository->add($brand);
            return $this->redirectToRoute('admin_brand_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('view/admin/brand/new.html.twig', [
            'brand' => $brand,
            'form' => $form,
            'current_menu' => 'admin_brand'
        ]);
    }


    #[Route('/{id}/edit', name: 'admin_brand_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Brand $brand, BrandRepository $brandRepository): Response
    {
        $form = $this->createForm(BrandType::class, $brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $brandRepository->add($brand);
            return $this->redirectToRoute('admin_brand_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('view/admin/brand/edit.html.twig', [
            'brand' => $brand,
            'form' => $form,
            'current_menu' => 'admin_brand'
        ]);
    }

    #[Route('/{id}', name: 'admin_brand_delete', methods: ['POST'])]
    public function delete(Request $request, Brand $brand, BrandRepository $brandRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$brand->getId(), $request->request->get('_token'))) {
            $brandRepository->remove($brand);
        }

        return $this->redirectToRoute('admin_brand_index', [], Response::HTTP_SEE_OTHER);
    }
}
