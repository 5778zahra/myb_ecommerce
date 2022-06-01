<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\Product;
//use Symfony\Component\Mime\Address;
//use Symfony\Bridge\Twig\Mime\TemplatedEmail;
//use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\Mailer\MailerInterface;
use App\Form\ContactType;
use App\Entity\Header;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        
        $products = $this->entityManager->getRepository(Product::class)->findByIsBest(1);
        $headers = $this->entityManager->getRepository(Header::class)->findAll();
        
        // $contactForm = $this->createForm(ContactType::class);
        // $contactForm->handleRequest($request);
        // $mail = new Mail();
        // $mail->send('dekzahra@outlook.fr', 'John Doe', 'Mon premier mail', 'Bonjour j\'espère que vous allez bien');

        // if ($contactForm->isSubmitted() && $contactForm->isValid()) {
        //     $data = $contactForm->getData();
        //     $email = (new TemplatedEmail())
        //     ->from(new Address($data['email'], $data['prenom'] . ' ' . $data['nom']))
        //     ->to(new Address('zahradh57@gmail.com'))
        //     ->replyTo((new Address($data['email'], $data['prenom'] . ' ' . $data['nom'])))
        //     ->subject($data['sujet'])
        //     ->htmlTemplate('email/contact.html.twig')
        //     ->context([
        //         'prenom' => $data['prenom'],
        //         'nom' => $data['nom'],
        //         'userEmail' => $data['email'],
        //         'sujet' => $data['sujet'],
        //         'message' => $data['message']
        //     ]);
        //     $mailer->send($email);
        //     return $this->redirectToRoute('app_home');
        // }

        return $this->render('home/index.html.twig', [
            'products'=> $products,
            'headers'=> $headers
        ]); 
        //     'contactForm' => $contactForm->createView()
        // ]);
           
    }
}
