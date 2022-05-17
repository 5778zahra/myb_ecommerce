<?php

namespace App\Controller;

use App\Classe\Mail;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $mail = new Mail();
        $mail->send('dekzahra@outlook.fr', 'John Doe', 'Mon premier mail', 'Bonjour j\'espère que vous allez bien');


        return $this->render('home/index.html.twig');
           
    }
}
