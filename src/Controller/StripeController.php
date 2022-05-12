<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Classe\Cart;
use Stripe\Checkout\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{
    #[Route('/commande/create-session', name: 'stripe_create_session')]
    public function index(Cart $cart): Response
    {

        $products_for_stripe = [];
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        foreach ($cart->getFull() as $product) {
            $products_for_stripe[] = [
                'price_data' => [
                    'currency'=> 'eur',
                    'unit_amount' => $product['product']->getPrice(),
                    'product_data' => [
                        'name'=> $product['product']->getName(),
                        'images'=> [$YOUR_DOMAIN."/uploads/".$product['product']->getIllustration()],
                    ],
                ],
                'quantity'=> $product['quantity'],
            ];
            

        }
        Stripe::setApiKey('sk_test_51KyXipD09fug7xqBQ1kAL9u07UGhLB0Lx81vMI5m68HpLohWJMDq2HgwKI0er2zjKilbE0oZpm2ioU4B8xddtfWT00mPY8iafY');

            
            $checkout_session = Session::create([
                'payment_method_types' => ['card'],
                'line_items'=> [ 
                    $products_for_stripe
                ],
                'mode' => 'payment',
                'success_url' => $YOUR_DOMAIN .'/success.html',
                'cancel_url' => $YOUR_DOMAIN .'/cancel.html',
            ]);
        // return $this->render('stripe/index.html.twig', [
        //     'controller_name' => 'StripeController',
        // ]);

        $response = new JsonResponse(['id' => $checkout_session->id]);
        return $response;
    }
}
