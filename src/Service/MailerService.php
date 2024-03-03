<?php

namespace App\Service;

use App\Entity\Course;
use App\Entity\User;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Twig\Environment;

class MailerService
{
    public const MAIL_ADRESSE = "Odyssey@gmail.com";
    public const ERROR_MESSAGE = "Nous sommes désolé, une erreur s'est produite, veuillez réessayer plus tard";
    public const SUCCESS_MESSAGE = "Votre message a bien été envoyé";

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
                ->to(self::MAIL_ADRESSE)
                ->subject('Formulaire de contact')
                ->html($this->twig->render('email/contact_form.html.twig', [
                    'data' => $data])
                );

            $this->mailerInterface->send($email);

            return self::SUCCESS_MESSAGE;

        } catch (\Exception $e) {

            return self::ERROR_MESSAGE;
        }
    }

    // send email when user create a account
    public function createAccount(User $user): string
    {
        try {
            $email = (new Email())
                ->from(self::MAIL_ADRESSE)
                ->to($user->getEmail())
                ->subject('Confirmation de la création de votre compte')
                ->html($this->twig->render('email/create_account.html.twig', [
                        'user' => $user,
                        'email' => self::MAIL_ADRESSE])
                );

            $this->mailerInterface->send($email);

            return 'Votre compte a bien été crée';

        }  catch (\Exception $e) {

            return self::ERROR_MESSAGE;
        }
    }

    // send mail when user enable/disable her account
    public function AccountMail(User $user, bool $disable = true): string
    {
        try {
            $email = (new Email())
                ->from(self::MAIL_ADRESSE)
                ->to($user->getEmail());

                if($disable) {
                    $email->subject('Confirmation de désactivation de votre compte')
                          ->html($this->twig->render('email/disable_account.html.twig', [
                            'user' => $user,
                            'email' => self::MAIL_ADRESSE])
                          );

                    $message = 'Votre compte a bien été désactivé';

                } else {
                    $email->subject('Confirmation de réactivation de votre compte')
                        ->html($this->twig->render('email/enable_account.html.twig', [
                            'user' => $user,
                            'email' => self::MAIL_ADRESSE])
                        );

                    $message = 'Vous venez de réactiver votre compte';
                }

                $this->mailerInterface->send($email);

                return $message;

        } catch (\Exception $e) {

            return self::ERROR_MESSAGE;
        }
    }

    // send mail to admin when teacher create a course
    public function sendCourseCreatedEmail(Course $course, User $user): string
    {
        try {

            $email = (new Email())
                ->from($user->getEmail())
                ->to(self::MAIL_ADRESSE)
                ->subject('Nouveau cours créé')
                ->html($this->twig->render('email/create_course.html.twig', [
                    'course' => $course,
                    'user' => $user])
                );

            $this->mailerInterface->send($email);

            return self::SUCCESS_MESSAGE;

        } catch (\Exception $e) {

            return self::ERROR_MESSAGE;
        }
    }

}
