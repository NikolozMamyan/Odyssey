<?php

namespace App\Controller;


use App\Entity\Course;
use App\Form\UserEditType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $course = $entityManager->getRepository(Course::class)->findBy(['createdBy' => $user]);

        return $this->render('user/index.html.twig', [
            'user' => $user,
            'course' => $course,
        ]);
    }
    #[Route('/user/edit/{id}', name: 'app_user_edit')]
    public function userEdit(EntityManagerInterface $entityManager, UserRepository $userRepository, Request $request, int $id): Response
    {
        $user= $userRepository->find($id);
        $form = $this->createForm(UserEditType::class, $user);
        $form -> handleRequest($request);

        if($form -> isSubmitted() && $form -> isValid()) {
            $entityManager-> persist($user);
            $entityManager->flush();

            return $this-> redirectToRoute('app_user');
        }
    
        return $this->render('user/userEdit.html.twig', [
            'userForm' =>$form,
            'user' => $user,
        ]);
    }

}
