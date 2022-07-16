<?php

namespace App\Controller;

//use App\Classe\Mail;
//use Mail;
use App\Entity\User;
use App\Classe\Mailer;
use App\Security\EmailVerifier;
use App\Form\RegistrationFormType;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;
    private Mailer $mailer;
    

    public function __construct(EmailVerifier $emailVerifier, Mailer $mailer)
    {
        $this->emailVerifier = $emailVerifier;
        $this->mailer = $mailer;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $notification = null;

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);

        $form->handleRequest($request); 

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            
            //$user->$form->getData();            

            $search_email = $entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());

            if (!$search_email) {

                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
//$password = $userPasswordHasher->HashPassword($user,$user->getPassword());

                //$user->setPassword($password);

                $entityManager->persist($user);
                $entityManager->flush();

                // generate a signed url and email it to the user
                $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                    (new TemplatedEmail())
                        ->from(new Address('myb@contact.com', 'myb Bot'))
                        ->to($user->getEmail())
                        ->subject('Please Confirm your Email')
                        ->htmlTemplate('registration/confirmation_email.html.twig')
                );

            
                return $this->redirectToRoute('app_home');


            }else{

                $notification = "L'email que vous avez renseigné existe déjà.";
            }          
                    
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'notification' => $notification
        ]);
    }

    //verifie si l'email est existant
    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason(), [], 'VerifyEmailBundle');

            return $this->redirectToRoute('app_account');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        // $this->mailer->sendMail('myb@contact.com', $user->getEmail(), 'Bienvenue sur notre boutique dédiée au artricles de sport', "registration/confirmation_email.html.twig", [
        //         "firstname"=>$user->getFirstname(),
        //         "lastname"=>$user->getLastname(), 
            //]);

        return $this->redirectToRoute('app_account');
    }

}

