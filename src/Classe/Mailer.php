<?php

namespace App\Classe;

//use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

Class Mailer
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendMail(string $from, string $to, string $subject, string $htmlTemplate, array $context)
    {
        $mail= (new TemplatedEmail())
        ->from($from)
        ->to($to)
        ->subject($subject)
        ->htmlTemplate($htmlTemplate)
        ->context($context);

        $this->mailer->send($mail);

    }

   
     
}