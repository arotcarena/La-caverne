<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/catégories')]
class AdminCategoryController extends AbstractController
{
    #[Route('/', name: 'admin_category_index', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('view/admin/category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'current_menu' => 'admin_category'
        ]);
    }

    #[Route('/new', name: 'admin_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CategoryRepository $categoryRepository): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->add($category);
            return $this->redirectToRoute('admin_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('view/admin/category/new.html.twig', [
            'category' => $category,
            'form' => $form,
            'current_menu' => 'admin_category'
        ]);
    }


    #[Route('/{id}/edit', name: 'admin_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Category $category, CategoryRepository $categoryRepository): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->add($category);
            return $this->redirectToRoute('admin_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('view/admin/category/edit.html.twig', [
            'category' => $category,
            'form' => $form,
            'current_menu' => 'admin_category'
        ]);
    }

    #[Route('/{id}', name: 'admin_category_delete', methods: ['POST'])]
    public function delete(Request $request, Category $category, CategoryRepository $categoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $categoryRepository->remove($category);
        }

        return $this->redirectToRoute('admin_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
