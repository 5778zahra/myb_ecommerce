<?php

namespace App\Controller;


//use Stripe\Stripe;
use App\Classe\Cart;
use App\Entity\Order;
use DateTimeImmutable;
use App\Entity\Carrier;
use App\Form\OrderType;
use App\Entity\OrderDetails;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
//use Stripe\Checkout\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/commande', name: 'app_order')]
    public function index(Cart $cart, Request $request): Response
    {
        if (!$this->getUser()->getAddresses()->getValues())
        {
            return $this->redirectToRoute('app_account_address');
        }

        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        //  $form->handleRequest($request);

        //  if ($form->isSubmitted() && $form->isValid()) {
        //       dd($form->getData()); 
        //  } 
        //je le retire car il s'applique dans la fonction add, c'est à ce moment que s'applique la commande

        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'cart'=> $cart->getFull()
            //'controller_name' => 'OrderController',
        
        ]);
    }

    #[Route('/commande/recapitulatif', name: 'app_order_recap', methods:['POST'] )]//rajouter la methods {"POST"}
    public function add(Cart $cart, Request $request): Response
    {
        if (!$this->getUser()->getAddresses()->getValues())
        {
            return $this->redirectToRoute('app_account_address');
        }

        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $date = new \DateTimeImmutable();
            $carriers = $form->get('carriers')->getData();
            $delivery = $form->get('addresses')->getData();
            $delivery_content = $delivery->getFirstname().' '.$delivery->getLastname();
            $delivery_content .= '<br/>'.$delivery->getPhone();
            

            if ($delivery->getCompany()) {
                $delivery_content .= '<br/>'.$delivery->getCompany();
            }

            $delivery_content .= '<br/>'.$delivery->getAddress();
            $delivery_content .= '<br/>'.$delivery->getPostal().''.$delivery->getCity();
            $delivery_content .= '<br/>'.$delivery->getCountry();
            //dd($delivery_content);
            
            //enregistrer ma commande Order()
            $order = new Order();
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setCarrierName($carriers->getName());
            $order->setCarrierPrice($carriers->getPrice());
            $order->setDelivery($delivery_content);
            $order->setIsPaid(0);
            //dd($order);

             $this->entityManager->persist($order);


            //Enregistrer mes produits OrderDetails()
            foreach ($cart->getFull() as $product) {
                $orderDetails = new OrderDetails();
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($product['product']->getName());
                $orderDetails->setQuantity($product['quantity']);
                $orderDetails->setPrice($product['product']->getPrice());
                $orderDetails->setTotal($product['product']->getPrice() * $product['quantity']);
                $this->entityManager->persist($orderDetails);

                                   
            }

            //$this->entityManager->flush();


        return $this->render('order/add.html.twig', [
            'cart' => $cart->getFull(),
            'carrier' => $carriers,
            'delivery' => $delivery_content,
            
           // 'controller_name' => 'OrderController',
        
        ]);

        }

        return $this->redirectToRoute('app_cart');
    }
}
