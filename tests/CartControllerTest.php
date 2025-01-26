<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CartControllerTest extends WebTestCase
{
    public function testAddProductToCart(): void
    {
        // Créer un client HTTP pour simuler des requêtes
        $client = static::createClient();

        // Simuler l'ajout d'un produit avec l'ID 1
        $client->request('GET', '/cart/add/1');

        // Vérifier la redirection après l'ajout
        // $this->assertResponseRedirects('/product/{slug}');

        // Suivre la redirection
        $client->followRedirect();

        // Vérifier qu'un message flash est affiché
        $this->assertSelectorTextContains('.flash-success', 'Produit correctement ajouté à votre panier.');
    }

    public function testCartPageDisplaysCartContents(): void
    {
        // Créer un client HTTP
        $client = static::createClient();

        // Accéder à la page du panier
        $crawler = $client->request('GET', '/mon-panier');

        // Vérifier que la page se charge correctement
        $this->assertResponseIsSuccessful();

        // Vérifier que le contenu attendu est affiché
        $this->assertSelectorExists('.container'); // Vérifie l'existence d'un conteneur
        // $this->assertSelectorTextContains('h1', 'Liste de mes produits'); // Ajustez selon le contenu attendu
    }
}