<?php

namespace App\Controller;

use App\Entity\User;
use App\Classe\Mailer;
use App\Entity\ResetPassword;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ResetPasswordController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/mot-de-passe-oublie', name: 'app_reset_password')]
    public function index(Request $request, Mailer $mailer): Response
    {
        if($this->getUser()) {
            return $this->redirectToRoute('app_home');
            //dd($user);
        }

        if($request->get('email')) {
            $user = $this->entityManager->getRepository(User::class)->findOneByEmail($request->get('email'));
            
            if($user) {
                $reset_password = new ResetPassword();
                $reset_password->setUser($user);
                $reset_password->setToken(uniqid());
                $reset_password->setCreatedAt(new \DateTimeImmutable());
                //dd($reset_password);
                $this->entityManager->persist($reset_password);
                $this->entityManager->flush();
                
                //envoi d'un mail à l'utilisateur + lien pour mise a jour du mdp

                $url = $this->generateUrl('app_update_password', [
                    'token' => $reset_password->getToken()
                ]);

                // $content = "Bonjour".$user->getFirstname()."<br/>Vous avez demandé à réinitialiser votre mot de passe sur le site 'Move Your Body'.<br/><br/>";
                // $content .= "Merci de bien vouloir cliquer sur le lien suivant <a href= '".$url."'>pour mettre à jour votre mot de passe</a>."; 
                // $mail = new Mail();
                // $mail->send($user->getEmail(), $user->getFirstname().' '.$user->getLastname(), 'Réinitialiser votre mot de passe sur votre espace membre', $content);
                $mailer->sendMail('myb@contact.com', $user->getEmail(),  'Réinitialiser votre mot de passe sur votre espace membre', "reset_password/email.html.twig", [
                    "firstname"=>$user->getFirstname(),
                    "lastname"=>$user->getLastname(), 
                    "url"=>$url
                ]);
            }
        }

        return $this->render('reset_password/index.html.twig');
           // 'controller_name' => 'ResetPasswordController',
       
    }

    #[Route('/modifier-mon-mot-de-passe/{token}', name: 'app_update_password')]
    public function update($token): Response
    {
    
        //  $reset_password = $this->entityManager->getRepository(ResetPassword::class)->findOneByToken($token);

        // if(!$reset_password) {
        //     return $this->redirectToRoute('reset_password');
        // }
        return $this->redirectToRoute('reset_password');
        
     }
    //verifier si le createdAt = now + 3h
    //  $now = new \DateTime();
    //  if ($now > $reset_password->getCreatedAt()->modify('+ 3 hour')) {
    //     $this->addFlash('notice', 'votre demande de mot de passe a expiré. Merci de la renouveller.');
    //     return $this->redirectToRoute('reset_password');
    //  }
 
    // dd($reset_password);

    //  #[Route('/reinitialiser-mon-mot-de-passe/{token}', name: 'app_reset_password')]
    // public function reset($token): Response
    // {
    
    //      $reset_password = $this->entityManager->getRepository(ResetPassword::class)->findOneByToken($token);

        
        
    //  }
    

}
