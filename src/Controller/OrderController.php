<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Form\OrderType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    /**
     * 1ère Étape du tunnel d'achat
     * Choix de l'adresse de livraison et du transporteur
     *
     * @return Response
     */
    #[Route('/commande/livraison', name: 'app_order')]
    public function index(): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $addresses = $user->getAddresses();
        
        if (count($addresses) == 0) {
            return $this->redirectToRoute('app_count_address_form');
        }
        
        $form = $this->createForm(OrderType::class, null, [
            'addresses' => $addresses,
            'action' => $this->generateUrl('app_order_summary')
        ]);
        
        return $this->render('order/index.html.twig', [
            'controller_name' => 'OrderController',
            'deliveryForm' => $form->createView(),
        ]);
    }

    /**
     * 2mé  Étape du tunnel d'achat
     * Récap de la commande de l'utilisateur
     * Insertion en BDD
     * Préparation du paiement vers stripe
     *
     * @return Response
     */
    #[Route('/commande/recapitulatif', name: 'app_order_summary')]
    public function add(Request $request, Cart $cart): Response
    {
        if ($request->getMethod() !='POST') {
            return $this->redirectToRoute('app_cart');
        }
        
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $addresses = $user->getAddresses();
        
        $form = $this->createForm(OrderType::class, null, [
            'addresses' => $addresses,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
            //Stocker les informations en BDD
        }
        
        return $this->render('order/summary.html.twig', [
            'choices' => $form->getData(),
            'cart' => $cart->getCart(),
            'totalWt' => $cart->getTotalWt(),
        ]);
    }
}