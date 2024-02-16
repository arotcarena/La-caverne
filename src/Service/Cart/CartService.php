<?php
namespace App\Service\Cart;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var ProductRepository
     */
    private $repository;
    
    /**
     * @var array [ ['product' => $product, 'quant' => $quant], []... ]
     */
    private $items = [];

    /**
     * @var int
     */
    private $total_price = 0;

    public function __construct(RequestStack $requestStack, ProductRepository $repository)
    {
        $this->session = $requestStack->getSession();
        $this->repository = $repository;
    }

    public function add(int $id, int $quant):void 
    {
        $cart = $this->session->get('cart', []);
        if(!empty($cart[$id]))
        {
            $cart[$id] += $quant;
        }
        else
        {
            $cart[$id] = $quant;
        }
        $this->session->set('cart', $cart);
    }

    public function delete(int $id, int $quant):void
    {
        $cart = $this->session->get('cart', []);
        if(!empty($cart[$id]))
        {
            $cart[$id] -= $quant;
        }
        if($cart[$id] <= 0)
        {
            unset($cart[$id]);
        }
        $this->session->set('cart', $cart);
    }

    public function getCartItems():array
    {
        if(empty($this->items))
        {
            $cart = $this->session->get('cart', []);
            foreach($cart as $id => $quant) 
            {
                $product = $this->repository->find($id);
                $this->items[] = [
                    'product' => $product,
                    'quant' => $quant
                ];
                $this->total_price += ($product->getPrice() * $quant);
            }
        }
        return $this->items;
    }

    public function getTotalPrice():int 
    {
        if($this->total_price === 0)
        {
            $this->getCartItems();
        }
        return $this->total_price;
    }
}