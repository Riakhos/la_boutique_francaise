<?php

namespace App\Controller\Account;

use App\Classe\Cart;
use App\Entity\Address;
use App\Form\AddressUserType;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AddressController extends AbstractController
{
	private $entityManagerInterface;
    
    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManagerInterface = $entityManagerInterface;
    }
	
	#[Route('/compte/adresses', name: 'app_account_addresses')]
    public function index(): Response
    {
        return $this->render('account/address/index.html.twig');
    }
    
    #[Route('/compte/adresses/delete/{id}', name: 'app_account_address_delete')]
    public function delete($id, AddressRepository $addressRepository): Response
    {
        $address = $addressRepository->findOneById($id);
        
        if (!$address OR $address->getUser()  != $this->getUser()) {
            return $this->redirectToRoute('app_account_addresses');
        }
        
        $this->addFlash(
            'success',
            'Votre adresse est correctement supprimée.'
        );
        
        $this->entityManagerInterface->remove($address);
        $this->entityManagerInterface->flush();
        
        return $this->redirectToRoute('app_account_addresses');
    }
    
    #[Route('/compte/adresse/ajouter/{id}', name: 'app_account_address_form', defaults: ['id' => null] )]
    public function form(Request $request, $id, AddressRepository $addressRepository, Cart $cart): Response
    {
        if ($id) {
            $address = $addressRepository->findOneById($id);
            if (!$address OR $address->getUser()  != $this->getUser()) {
                return $this->redirectToRoute('app_account_addresses');
            }
        } else {
            $address = new Address();
            $address->setUser($this->getUser());
        }
        
        $form = $this->createForm(AddressUserType::class, $address);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManagerInterface->persist($address);
            $this->entityManagerInterface->flush();
            
            $this->addFlash(
                'success',
                'Votre adresse est correctement sauvegardée.'
            );

            if ($cart->fullQuantity() > 0) {
                return $this->redirectToRoute('app_order');
            }
            
            return $this->redirectToRoute('app_account_addresses');
        }
        
        return $this->render('account/address/form.html.twig', [
            'addressForm'=> $form, 
        ]);
    }
}