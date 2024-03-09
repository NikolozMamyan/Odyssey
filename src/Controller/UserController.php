<?php

namespace App\Controller;


use App\Entity\User;
use App\Entity\Course;
use App\Form\UserEditType;
use App\Service\MailerService;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{

    private MailerService $mailer;

    /**
     * initializes the mailer service
     *
     * @param MailerService $mailer
     */
    public function __construct(MailerService $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Display profile information about user (watchlist, own courses)
     *
     * @param EntityManagerInterface $entityManager
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/user', name: 'app_user')]
    public function index(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        // Allows to reactivate the account
        if (!$user->isIsActive()) {
            $user->setIsActive(true);

            $entityManager->flush();

            //send mail to confirm enable account user
            $message = $this->mailer->AccountMail($user, false);

            // display message when user reactivate her account
            if ($message != MailerService::ERROR_MESSAGE) {
                $this->addFlash('warning', $message);
            } else {
                $this->addFlash('danger', $message);
            }
        }

        $course = $entityManager->getRepository(Course::class)->findBy(['createdBy' => $user], ['id' => 'DESC']);
        $notes = $entityManager->getRepository(Course::class)->getAverageNotes();

        $pagination = $paginator->paginate(
            $course,
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


    /**
     * Display & process form to update your own courses
     *
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $userRepository
     * @param Request $request
     * @param int $id
     * @return Response
     */
    #[Route('/user/edit/{id}', name: 'app_user_edit')]
    public function userEdit(EntityManagerInterface $entityManager, UserRepository $userRepository, Request $request, int $id): Response
    {
        $user = $userRepository->find($id);
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user');
        }

        return $this->render('user/userEdit.html.twig', [
            'userForm' => $form,
            'user' => $user,
        ]);
    }

    /**
     * feature to disable account
     *
     * @param int $id
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    #[Route('/user/disable/{id}', name: 'app_user_disable')]
    public function disableUser(int $id, EntityManagerInterface $em): RedirectResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($user) {
            $user->setIsActive(false);
            $em->flush();

            //send mail to confirm disable account user
            $message = $this->mailer->AccountMail($user);

            // display message when user reactivate her account
            if ($message != MailerService::ERROR_MESSAGE) {
                $this->addFlash('warning', $message);
            } else {
                $this->addFlash('danger', $message);
            }
        }

        return $this->redirectToRoute('app_logout');
    }
}
