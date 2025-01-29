<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressUserType;
use App\Form\PasswordUserType;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountController extends AbstractController
{
    private $entityManagerInterface;
    
    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManagerInterface = $entityManagerInterface;
    }
    
    #[Route('/compte', name: 'app_account')]
    public function account(): Response
    {
        return $this->render('account/account.html.twig');
    }
    
    #[Route('/compte/modifier-mot-de-passe', name: 'app_account_modify_pwd')]
    public function password(Request $request, UserPasswordHasherInterface $userPasswordHasherInterface): Response
    {
        $user = $this->getUser();
        
        $form = $this->createForm(PasswordUserType::class, $user, [
            'passwordHasher' => $userPasswordHasherInterface 
        ]);

        $form->handleRequest($request);
        
        // Si le formulaire est soumis alors :
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManagerInterface->flush();
            $this->addFlash(
                'success',
                'Votre mot de passe est correctement mis à jour.'
            );
        }
        
        return $this->render('account/password.html.twig', [
            'modifyPwd' => $form->createView()
        ]);
    }

    #[Route('/compte/adresses', name: 'app_account_addresses')]
    public function addresses(): Response
    {
        return $this->render('account/addresses.html.twig');
    }
    
    #[Route('/compte/adresses/delete/{id}', name: 'app_account_address_delete')]
    public function addressDelete($id, AddressRepository $addressRepository): Response
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
    public function addressForm(Request $request, $id, AddressRepository $addressRepository): Response
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
            
            return $this->redirectToRoute('app_account_addresses');
        }
        
        return $this->render('account/addressForm.html.twig', [
            'addressForm'=> $form, 
        ]);
    }
}