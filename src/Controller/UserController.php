<?php

namespace App\Controller;


use App\Entity\Course;
use App\Entity\User;
use App\Form\UserEditType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{

    const MAIL_ADRESSE = "Odyssey@gmail.com";

    private MailerInterface $mailer;


    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }


    #[Route('/user', name: 'app_user')]
    public function index(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request): Response
    {
        $user = $this->getUser();
        $course = $entityManager->getRepository(Course::class)->findBy(['createdBy' => $user],['id' => 'DESC']);
        $notes = $entityManager->getRepository(Course::class)->getAverageNotes();

        $pagination = $paginator->paginate(
            $course, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            6 /*limit per page*/
        );

        return $this->render('user/index.html.twig', [
            'user' => $user,
            'course' => $course,
            'notes' => $notes,
            'pagination' => $pagination
        ]);
    }
    #[Route('/user/edit/{id}', name: 'app_user_edit')]
    public function userEdit(EntityManagerInterface $entityManager, UserRepository $userRepository, Request $request, int $id): Response
    {
        $user = $userRepository->find($id);
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($user);
            $entityManager->flush();

            return $this-> redirectToRoute('app_user');
        }
    
        return $this->render('user/userEdit.html.twig', [
            'userForm' =>$form,
            'user' => $user,
        ]);
    }

    #[Route('/user/disable/{id}', name: 'app_user_disable')]
    public function disableUser(int $id, EntityManagerInterface $em): RedirectResponse
      {

        $user = $this->getUser();

        if ($user) {
          $user->setIsActive(false);
          $em->flush();

          //send mail to confirm disable account user
          $this->sendDisableAccountMail($user);

          $this->addFlash('success', "Votre compte a bien été désactivé");

        }

        return $this->redirectToRoute('app_logout');
      }


    private function sendDisableAccountMail(User $user): void
    {
        $email = (new Email())
            ->from(self::MAIL_ADRESSE)
            ->to($user->getEmail())
            ->subject('Confirmation de désactivation de votre compte')
            ->html('<h2>Cher(e) ' . $user->getFirstNameUser() . ' ' . $user->getLastNameUser() . '</h2><br>' .
                '<p>C\'est avec regret que nous vous informons que votre compte a bien été désactivé.<br><br> Nous sommes sincèrement désolés de vous voir partir.
                Si vous avez des questions ou des préoccupations concernant cette désactivation, n\'hésitez pas à nous contacter à l\'adresse ' . self::MAIL_ADRESSE . '.
                <br><br>Nous tenons à vous remercier pour le temps que vous avez passé avec nous et espérons que vous avez trouvé de la valeur dans notre service.
                Si vous décidez de revenir à l\'avenir, nous serons ravis de vous accueillir à nouveau.<br><br>
                Cordialement,</p>');

        $this->mailer->send($email);
    }

}
