<?php

namespace App\Controller\Account;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WishlistController extends AbstractController
{
    #[Route('/compte/liste-de-souhait', name: 'app_account_wishlist')]
    public function index(): Response
    {
        return $this->render('account/wishlist/index.html.twig', [
            
        ]);
    }
    
    #[Route('/compte/liste-de-souhait/add/{id}', name: 'app_account_wishlist_add')]
    public function add(ProductRepository $productRepository, $id, EntityManagerInterface $em, Request $request): Response
    {
        // 1.Récupérer l'objet du produit souhaité
        $product = $productRepository->findOneById($id);
        
        // 2. Si produit existant, ajouter le produit à la wishlist.
        if ($product) {
            
            /** @var \App\Entity\User $user */
            $user = $this->getUser();
            
            $user->addWishlist($product);
            
            // 3. Sauvegarder en BDD
            $em->flush();
        }
        
        $this->addFlash(
            'success',
            'Produit correctement ajoutée à votre liste de souhait.'
        );
        
        return $this->redirect($request->headers->get('referer'));
    }
    
    #[Route('/compte/liste-de-souhait/remove/{id}', name: 'app_account_wishlist_remove')]
    public function remove(ProductRepository $productRepository, $id, EntityManagerInterface $em, Request $request): Response
    {
        // 1.Récupérer l'objet du produit a supprimer
        $product = $productRepository->findOneById($id);
        
        // 2. Si produit existant, supprimer le produit de la wishlist.
        if ($product) {
            $this->addFlash(
                'success',
                'Produit correctement supprimé à votre liste de souhait.'
            );
            
            /** @var \App\Entity\User $user */
            $user = $this->getUser();
            
            $user->removeWishlist($product);
            
            // 3. Sauvegarder en BDD
            $em->flush();
        } else {
            $this->addFlash(
                'danger',
                'Produit introuvable.'
            );
        }
        
        return $this->redirect($request->headers->get('referer'));
    }
}