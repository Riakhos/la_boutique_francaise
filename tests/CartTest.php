<?php

namespace App\Tests\Classe;

use App\Classe\Cart;
use PHPUnit\Framework\TestCase;
use App\Controller\ProductController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class CartTest extends TestCase
{
    public function testAddProductToCart(): void
    {
        // Simuler une session
        $sessionStorage = new MockArraySessionStorage();
        $session = new Session($sessionStorage);

        // Créer une requête avec une session
        $request = new Request();
        $request->setSession($session);

        // Injecter la requête dans RequestStack
        $requestStack = new RequestStack();
        $requestStack->push($request);

        // Créer une instance de Cart
        $cart = new Cart($requestStack);

        // Simuler un produit
        $product = $this->createMock(ProductController::class);
        $product->method('getId')->willReturn(1);

        // Ajouter le produit au panier
        $cart->add($product);

        // Vérifier le contenu de la session
        $cartData = $cart->getCart();

        // Assertions
        $this->assertArrayHasKey(1, $cartData); // Le produit doit être dans le panier
        $this->assertEquals(1, $cartData[1]['qty']); // La quantité doit être de 1
        $this->assertSame($product, $cartData[1]['object']); // Le produit stocké doit être le même objet
    }

    public function testIncreaseQuantityForExistingProduct(): void
    {
        // Simuler une session
        $sessionStorage = new MockArraySessionStorage();
        $session = new Session($sessionStorage);

        // Créer une requête avec une session
        $request = new Request();
        $request->setSession($session);

        // Injecter la requête dans RequestStack
        $requestStack = new RequestStack();
        $requestStack->push($request);

        // Créer une instance de Cart
        $cart = new Cart($requestStack);

        // Simuler un produit
        $product = $this->createMock(ProductController::class);
        $product->method('getId')->willReturn(1);

        // Ajouter le produit deux fois au panier
        $cart->add($product);
        $cart->add($product);

        // Vérifier le contenu de la session
        $cartData = $cart->getCart();

        // Assertions
        $this->assertArrayHasKey(1, $cartData); // Le produit doit être dans le panier
        $this->assertEquals(2, $cartData[1]['qty']); // La quantité doit être augmentée
    }

    public function testEmptyCartInitially(): void
    {
        // Simuler une session
        $sessionStorage = new MockArraySessionStorage();
        $session = new Session($sessionStorage);

        // Créer une requête avec une session
        $request = new Request();
        $request->setSession($session);

        // Injecter la requête dans RequestStack
        $requestStack = new RequestStack();
        $requestStack->push($request);

        // Créer une instance de Cart
        $cart = new Cart($requestStack);

        // Vérifier que le panier est vide au début
        $cartData = $cart->getCart();
        $this->assertEmpty($cartData);
    }
}