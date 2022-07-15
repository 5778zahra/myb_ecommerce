<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Classe\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderSuccessController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    #[Route('/commande/merci/{stripeSessionId}', name: 'app_order_validate')]
    public function index(Cart $cart, Mailer $mailer, $stripeSessionId): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);

        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        if (!$order->getIsPaid()) {
            //valider la session "cart"
            $cart->remove();

            //Modifier le statut isPaid de notre commande en mettant 1
            $order->setState(1);
            $this->entityManager->flush();

            //envoyer un mail de confirmation de commande a l'utilisateur
            $mailer->sendMail('myb@contact.com', $user->getEmail(), 'Confirmation de votre commande', "order_success/index.html.twig", [
                "firstname"=>$user->getFirstname(),
                "lastname"=>$user->getLastname(), 
            ]);    

        }
        
      

//dd($order);
        
        return $this->render('order_success/index.html.twig', [
            'order'=> $order
        ]);
         
    }
}
