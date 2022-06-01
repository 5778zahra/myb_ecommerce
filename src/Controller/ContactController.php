<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, SluggerInterface $slugger, MailerInterface $mailer): Response
    {
        $contactForm = $this->createForm(ContactType::class);
        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()) {

            $contact = $contactForm->getData();

            $email = (new TemplatedEmail())
                ->from(new Address($contact['email']), $contact['firstName'] .''. $contact['lastName'])
                ->replyTo(new Address($contact['email']), $contact['firstName'] .''. $contact['lastName'])
                ->to(new Address('zahradh57@gmail.com'))
                ->subject('MYB-Demande de contact -'. $contact['subject'])
                ->htmlTemplate('contact/contact_email.html.twig')
                ->context([
                    'firstname'=> $contact['firstname'],
                    'lastName'=> $contact['lastName'],
                    'emailAddress'=> $contact['email'],
                    'subject'=> $contact['subject'],
                    'message'=> $contact['message']
                ]);
            if ($contact['attachement'] !== null) {
                $originalFilename = pathinfo($contact['attachment']->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($orininalFileName);
                $newFileName = $safeFileName . '.' . $contact['attachment']->guessExtension();
                $email->attachFromPath($contact['attachment']->getPathName(), $newFileName);
            }
            $mailer->send($email);
            $this->addFlash('success', 'votre message a bien été envoyé. Nous vous répondrons dans les plus brefs délais ');
            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/index.html.twig', [
            'contactForm' => $contactForm->createView()
        ]);
    }
}
