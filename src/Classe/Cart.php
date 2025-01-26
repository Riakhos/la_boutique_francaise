<?php

namespace App\Classe;

use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{
	public function __construct(private RequestStack $requestStack,) 
	{
        // Accessing the session in the constructor is *NOT* recommended, since
        // it might not be accessible yet or lead to unwanted side-effects
        // $this->session = $requestStack->getSession();
    }

	public function add($product)
	{
		// Appeler la session de symfony
		$session = $this->requestStack->getSession();
		
		// Récupérer le panier actuel (ou initialiser un tableau vide)
        $cart = $session->get('cart', []);
		
		// Vérifier si le produit existe déjà dans le panier
        $productId = $product->getId();
        if (isset($cart[$productId])) {
            // Augmenter la quantité si le produit est déjà dans le panier
            $cart[$productId]['qty']++;
        } else {
            // Ajouter le produit avec une quantité de 1
            $cart[$productId] = [
                'object' => $product,
                'qty' => 1
            ];
        }
		
		// Enregistrer le panier mis à jour dans la session
        $session->set('cart', $cart);
	}

	public function getCart()
	{
		return $this->requestStack->getSession()->get('cart');
	}
}