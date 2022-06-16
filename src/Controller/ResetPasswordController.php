<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\ResetPassword;
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
    public function index(Request $request): Response
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

                $url = $this->generateUrl('update_password', [
                    'token' => $reset_password->getToken()
                ]);

                $content = "Bonjour".$user->getFirstname()."<br/>Vous avez demandé à réinitialiser votre mot de passe sur le site 'Move Your Body'.<br/><br/>";
                $content .= "Merci de bien vouloir cliquer sur le lien suivant <a href= '".$url."'>pour mettre à jour votre mot de passe</a>."; 
                 $mail = new Mail();
                 $mail->send($user->getEmail(), $user->getFirstname().' '.$user->getLastname(), 'Réinitialiser votre mot de passe sur votre espace membre', $content);
            }
        }

        return $this->render('reset_password/index.html.twig');
           // 'controller_name' => 'ResetPasswordController',
       
    }

    #[Route('/modifier-mon-mot-de-passe/{token}', name: 'app_update_password')]
    public function update($token): Response
    {
        dd($token);
    //     $reset_password = $this->entityManager->getRepository(ResetPassword::class)->findOneByToken($token);

    //     if(!$reset_password) {
    //         return $this->redirectToRoute('reset_password');
    //     }

    //     dd($reset_password);
     }
}
