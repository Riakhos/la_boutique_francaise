<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class CartController extends AbstractController
{
    #[Route('/mon-panier', name: 'app_cart')]
    public function cart(Cart $cart): Response
    {
        return $this->render('cart/cart.html.twig', [
            'cart' => $cart->getCart(),
            'totalWt' => $cart->getTotalWt(),
            'total' => $cart->getTotal(),
        ]);
    }
    
    #[Route('/cart/add/{id}', name: 'app_cart_add')]
    public function add($id, Cart $cart, ProductRepository $productRepository, Request $request): Response
    {
        
        $product = $productRepository->findOneById($id);
        
        $cart->add($product);

        $this->addFlash(
            'success',
            'Produit correctement ajoutée à votre panier.'
        );
        
        return $this->redirect($request->headers->get('referer'));
    }
    
    #[Route('/cart/decrease/{id}', name: 'app_cart_decrease')]
    public function decrease($id, Cart $cart): Response
    {
        $cart->decrease($id);

        $this->addFlash(
            'success',
            'Produit correctement supprimée de votre panier.'
        );
        
        return $this->redirectToRoute('app_cart');
    }
    
    #[Route('/cart/remove', name: 'app_cart_remove')]
    public function remove(Cart $cart): Response
    {
        $cart->remove();
        
        return $this->redirectToRoute('app_home');
    }
}