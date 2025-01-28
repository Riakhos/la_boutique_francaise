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

    /**
     * add
     * Fonction permettant l'ajout d'un produit au panier
     *
     * @param [type] $product
     * @return mixed
     */
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

    /**
     * getCard
     * Fonction retournant le panier
     *
     * @return mixed
     */
	public function getCart()
	{
		return $this->requestStack->getSession()->get('cart');
	}
	
    /**
     * remove
     * Fonction permettant de supprimer totalement le panier
     *
     * @return mixed
     */
    public function remove()
	{
		return $this->requestStack->getSession()->remove('cart');
	}

    /**
     * decrease
     * Fonction permettant la diminution d'un produit dans le panier
     *
     * @param [type] $id
     * @return mixed
     */
    public function decrease($id)
    {
        $cart = $this->getCart();

        if ($cart[$id]['qty'] > 1) {
            $cart[$id]['qty'] = $cart[$id]['qty'] - 1;
        } else {
            unset($cart[$id]);
        }

        $this->requestStack->getSession()->set('cart', $cart);
    }

    /**
     * fullQuantity
     * Fonction retournant le nombre total de produit dans le panier
     *
     * @return mixed
     */
    public function fullQuantity()
    {
        $cart = $this->getCart();
        $quantity = 0;
        
        if (!isset($cart)) {
            return $quantity;
        }
        
        foreach ($cart as $product) {
            $quantity = $quantity + $product['qty'];
        }
        
        return $quantity;
    }

    /**
     * getTotal
     * Fonction retournant le prix total H.T des produits dans le panier
     *
     * @return mixed
     */
    public function getTotal()
    {
        $cart = $this->getCart();
        $price = 0;

        if (!isset($cart)) {
            return $price;
        }
        
        foreach ($cart as $product) {
            $price = $price + ($product['object']->getPrice() * $product['qty']);
        }
        return $price;
    }
    
    /**
     * getTotalWt
     * Fonction retournant le prix total T.T.C des produits dans le panier
     *
     * @return mixed
     */
    public function getTotalWt()
    {
        $cart = $this->getCart();
        $price = 0;
        
        if (!isset($cart)) {
            return $price;
        }
        
        foreach ($cart as $product) {
            $price = $price + ($product['object']->getPriceWt() * $product['qty']);
        }
        return $price;
    }
}