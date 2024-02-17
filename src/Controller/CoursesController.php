<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Teacher;
use App\Entity\Category;
use App\Form\CourseType;
use App\Form\CategoryFilterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CoursesController extends AbstractController
{
   
    #[Route('/courses', name: 'app_courses')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $form = $this->createForm(CategoryFilterType::class);
        $form->handleRequest($request);
    
        $categories = $entityManager->getRepository(Category::class)->findAll();
    
        $courses = $entityManager->getRepository(Course::class)->findAll();
    
        if ($form->isSubmitted() && $form->isValid()) {
      
            $selectedCategory = $form->get('name')->getData();
    
            if ($selectedCategory) {
             
                $courses = $entityManager->getRepository(Course::class)->findByCategory($selectedCategory);
            }
        }
    
        return $this->render('courses/index.html.twig', [
            'form' => $form->createView(),
            'courses' => $courses,
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
    public function create(EntityManagerInterface $entityManager, Request $request): Response
    {
        //get a teacher from the database latest teacher
        $teacher = $entityManager->getRepository(Teacher::class)->findOneBy([], ['id' => 'DESC']);
        $course = new Course();
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $course->setAuthor($teacher);
            $entityManager->persist($course);
            $entityManager->flush();

            //this is the return when we have the course page
            // return $this->redirectToRoute('app_courses_show', ['id' => $course->getId()]);

            //for now we will return to the courses list
            return $this->redirectToRoute('app_courses');
        }

        return $this->render('courses/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/courses/{id<\d*>}/edit', name: 'app_course_edit')]
    public function edit($id, EntityManagerInterface $entityManager, Request $request): Response
    {
        $course = $entityManager->getRepository(Course::class)->find($id);
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_course_show', ['id' => $course->getId()]);
        }

        return $this->render('courses/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/courses/{id<\d*>}/delete', name: 'app_course_delete')]
    public function delete($id, EntityManagerInterface $entityManager): RedirectResponse
    {
        $course = $entityManager->getRepository(Course::class)->find($id);
        $entityManager->remove($course);
        $entityManager->flush();

        return $this->redirectToRoute('app_courses');
    }


    // this function add course in the collection of courses in User Entity
    #[Route('/courses/{id<\d*>}/participate', name: 'app_course_participate')]
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
    #[Route('/user/{id<\d*>}/remove', name: 'app_course_participate_remove')]
    public function RemoveParticipateCourses(int $id, EntityManagerInterface $entityManager): RedirectResponse
    {
        $user = $this->getUser();
        $course = $entityManager->getRepository(Course::class)->find($id);

        $user->removeParticipateCourse($course);
        $entityManager->remove($course);
        $entityManager->flush();

        return $this->redirectToRoute('app_user', ['user' => $user]);
    }
}
