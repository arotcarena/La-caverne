<?php

namespace App\Controller;

use App\Service\Cart\CartService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    /**
     * @var CartService
     */
    private $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    #[Route('/mon-panier', name: 'cart_index')]
    public function index(): Response
    {
        return $this->render('view/cart/index.html.twig', [
            'current_menu' => 'cart',
            'items' => $this->cartService->getCartItems(),
            'total' => $this->cartService->getTotalPrice()
        ]);
    }

    #[Route('/mon-panier/ajout/ref-{id}/quant-{quant}', name: 'cart_add')]
    public function add(int $id, int $quant): Response
    {
        $this->cartService->add($id, $quant);

        $this->addFlash('success', 'L\'article a bien été ajouté !');
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/mon-panier/suppr/ref-{id}/quant-{quant}', name: 'cart_delete')]
    public function delete(int $id, int $quant): Response
    {
        $this->cartService->delete($id, $quant);

        $this->addFlash('success', 'L\'article a bien été supprimé !');
        return $this->redirectToRoute('cart_index');
    }

}
