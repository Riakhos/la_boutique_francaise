<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Form\OrderType;
use App\Entity\OrderDetail;
use Doctrine\ORM\EntityManagerInterface;
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
    public function add(Request $request, Cart $cart, EntityManagerInterface $em): Response
    {
        if ($request->getMethod() !='POST') {
            return $this->redirectToRoute('app_cart');
        }
        
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $addresses = $user->getAddresses();

        $products = $cart->getCart();
        
        $form = $this->createForm(OrderType::class, null, [
            'addresses' => $addresses,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
            
            //Création de la chaîne adresse
            $addressObj = $form->get('addresses')->getData();
            $address = $addressObj->getFirstname(). ' - '.$addressObj->getLastname().'<br/>';
            $address .= $addressObj->getAddress().'<br/>';
            $address = $addressObj->getPostal(). ' - '.$addressObj->getCity().'<br/>';
            $address .= $addressObj->getCountry().'<br/>';
            $address .= $addressObj->getPhone();
            
            //Stocker les informations en BDD
            $order = new Order();
            $order->setUser($this->getUser());
            $order->setCreatedAt(new \DateTime());
            $order->setState(1);
            $order->setCarrierName($form->get('carriers')->getData()->getName());
            $order->setCarrierPrice($form->get('carriers')->getData()->getPrice());
            $order->setDelivery($address);
            
            foreach ($products as $product) {
                $orderDetail = new OrderDetail();
                $orderDetail->setProductName($product['object']->getName());
                $orderDetail->setProductImage($product['object']->getImage());
                $orderDetail->setProductPrice($product['object']->getPrice());
                $orderDetail->setProductTva($product['object']->getTva());
                $orderDetail->setProductQuantity($product['qty']);
                $order->addOrderDetail($orderDetail);
            }
            
            $em->persist($order);
            $em->flush();
            
        }
        
        return $this->render('order/summary.html.twig', [
            'choices' => $form->getData(),
            'cart' => $products,
            'totalWt' => $cart->getTotalWt(),
        ]);
    }
}