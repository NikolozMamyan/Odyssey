<?php

namespace App\Service;

use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Twig\Environment;

class MailerService
{
    private MailerInterface $mailerInterface;
    private Environment $twig;

    public function __construct(MailerInterface $mailerInterface, Environment $twig)
    {
        $this->mailerInterface = $mailerInterface;
        $this->twig = $twig;
    }

    // send email from contact form home page
    public function sendMail(array $data): string
    {
        try {
            $email = (new Email())
                ->from(new Address($data['email'], $data['name']))
                ->to('Odyssey@gmail.com')
                ->subject('Formulaire de contact')
                ->html($this->twig->render('component/contact_mail.html.twig', ['data' => $data]));

            $this->mailerInterface->send($email);

            return 'Votre message a bien été envoyé';

        } catch (\Exception $e) {
            return 'Nous sommes désolé, une erreur s\'est produite lors de l\'envoi du mail, veuillez réessayer plus tard';
        }
    }
}
