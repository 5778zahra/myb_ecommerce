<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountAddressController extends AbstractController
{
    #[Route('/compte/adresses', name: 'app_account_address')]
    public function index(): Response
    {
        dd($this->getUser());
        return $this->render('account/address.html.twig', [
            //'controller_name' => 'AccountAddressController',
        ]);
    }
}
