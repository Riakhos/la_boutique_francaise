<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $user= new User();
        
        $form = $this->createForm(RegisterUserType::class, $user);

        $form->handleRequest($request);
        
        // Si le formulaire est soumis alors :
        if ($form->isSubmitted() && $form->isValid()) {
            // Tu enregistres les datas en BDD
            $entityManagerInterface->persist($user);
            $entityManagerInterface->flush();
            $this->addFlash(
                'success',
                'Votre compte est correctement créé, veuillez-vous connecter.'
            );
            return $this->redirectToRoute('app_login');
        }
        
        return $this->render('register/index.html.twig', [
            'registerForm' => $form->createView()
        ]);
    }
}