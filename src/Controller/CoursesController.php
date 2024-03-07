<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Category;
use App\Form\CourseType;
use App\Form\CategoryFilterType;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CoursesController extends AbstractController
{
    /**
     * Display the course list and search
     *
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    #[Route('/courses', name: 'app_courses')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $form = $this->createForm(CategoryFilterType::class);

        $form->handleRequest($request);

        $notes = $entityManager->getRepository(Course::class)->getAverageNotes();

        $courses = $entityManager->getRepository(Course::class)->findAll();

        if ($form->isSubmitted() && $form->isValid()) {

            $selectedCategory = $form->getData()['name'];

            if ($selectedCategory) {
                $courses = $entityManager->getRepository(Course::class)->findByCategory($selectedCategory);
            }
        }

        return $this->render('courses/index.html.twig', [
            'form' => $form->createView(),
            'courses' => $courses,
            'notes' =>  $notes
        ]);
    }


    #[Route('/courses/{id<\d*>}', name: 'app_course_show')]
    public function show($id, EntityManagerInterface $entityManager): Response
    {
        $course = $entityManager->getRepository(Course::class)->find($id);

        if (!$course) {
            throw $this->createNotFoundException('No course found for id ' . $id);
        }

        return $this->render('courses/show.html.twig', [
            'course' => $course,
        ]);
    }

    #[Route('/courses/create', name: 'app_course_create')]
    public function create(EntityManagerInterface $entityManager, Request $request, MailerService $mailer): Response
    {
        $categories = $entityManager->getRepository(Category::class)->findAll();

        $course = new Course();

        $form = $this->createForm(CourseType::class, $course, [
            'categories' => $categories,
        ]);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $course->setCreatedBy($this->getUser());
            $course->setStatus("en attente");

            $entityManager->persist($course);
            $entityManager->flush();

            //send an email after the course have been created
            $message = $mailer->sendCourseCreatedEmail($course, $this->getUser());

            // display message
            if ($message === MailerService::SUCCESS_MESSAGE) {
                $this->addFlash('warning', $message);
            } else {
                $this->addFlash('danger', $message);
            }

            //for now, we will return to the courses list
            return $this->redirectToRoute('app_user');
        }

        return $this->render('courses/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/courses/edit/{id<\d*>}', name: 'app_course_edit')]
    public function edit($id, EntityManagerInterface $entityManager, Request $request): Response
    {
        $course = $entityManager->getRepository(Course::class)->find($id);
        $categories = $entityManager->getRepository(Category::class)->findAll();

        $form = $this->createForm(CourseType::class, $course, [
            'categories' => $categories,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_course_show', ['id' => $course->getId()]);
        }

        return $this->render('courses/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/courses/delete/{id<\d*>}', name: 'app_course_delete')]
    public function delete(EntityManagerInterface $entityManager, int $id): RedirectResponse
    {
        $course = $entityManager->getRepository(Course::class)->find($id);
        $entityManager->remove($course);
        $entityManager->flush();

        // display message
        $this->addFlash('danger', 'Le cours a bien été supprimé');

        return $this->redirectToRoute('app_user');
    }


    // this function add course in the collection of courses in User Entity
    #[Route('/courses/participate/{id<\d*>}', name: 'app_course_participate')]
    public function participateCourses(int $id, EntityManagerInterface $entityManager): RedirectResponse
    {
        $user = $this->getUser();
        $course = $entityManager->getRepository(Course::class)->find($id);

        $user->addParticipateCourse($course);

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_course_show', ['id' => $course->getId()]);
    }

    // this function remove course in the collection of courses in User Entity
    #[Route('/user/remove/{id<\d*>}', name: 'app_course_participate_remove')]
    public function RemoveParticipateCourses(int $id, EntityManagerInterface $entityManager): RedirectResponse
    {
        $user = $this->getUser();
        $course = $entityManager->getRepository(Course::class)->find($id);

        $user->removeParticipateCourse($course);
        $entityManager->flush();

        $this->addFlash('success', 'Le cours bien été supprimé de ta liste');

        return $this->redirectToRoute('app_user', ['user' => $user]);
    }
}
