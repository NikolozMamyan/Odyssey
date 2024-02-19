<?php

namespace App\Controller;


use App\Entity\Course;
use App\Entity\Teacher;
use App\Form\UserEditType;
use App\Repository\UserRepository;
use App\Repository\CourseRepository;
use App\Repository\TeacherRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(EntityManagerInterface $entityManager, UserRepository $userRepository, Request $request): Response
    {
        $user= $this->getUser();
        $course = $entityManager->getRepository(Course::class)->findAll();
        // dd($user);
    
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

    #[Route('/courses/{id}/delete', name: 'app_course_delete')]
    public function delete(EntityManagerInterface $entityManager,CourseRepository $courseRepository, TeacherRepository $teacherRepository, int $id) :Response
    {
        $course = $courseRepository->find($id);
        $entityManager->remove($course);
        $entityManager->flush();

        return $this->redirectToRoute('app_user');
    }
}
