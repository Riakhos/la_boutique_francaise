<?php

namespace App\Controller;

use App\Classe\Mail;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $mail = new Mail();
        $mail->send('jotadex850@arinuse.com', 'John Doe', 'Bonjour, test de lma classe Mail', 'Mon premier mail');
        
        return $this->render('home/index.html.twig', );
    }
}