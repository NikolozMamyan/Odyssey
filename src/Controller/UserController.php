<?php

namespace App\Controller;


use App\Entity\Course;
use App\Form\UserEditType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request): Response
    {
        $user = $this->getUser();
        $course = $entityManager->getRepository(Course::class)->findBy(['createdBy' => $user]);
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

}
