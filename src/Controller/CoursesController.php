<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Category;
use App\Form\SearchType;
use App\Model\SearchData;
use App\Form\CategoryFilterType;
use App\Repository\CourseRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CoursesController extends AbstractController
{
   
    #[Route('/courses', name: 'app_courses')]
    public function index(EntityManagerInterface $entityManager): Response
    {

        $coursesRepository = $entityManager->getRepository(Course::class);
        $courses = $coursesRepository->findAll();

        if (!$courses) {
            throw $this->createNotFoundException('No courses found');
        }

        return $this->render('courses/index.html.twig', [
            'courses' => $courses,
        ]);
    }

    #[Route('/courses/{id}', name: 'app_course_show')]
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

    #[Route('/courses', name: 'app_courses_index')]
    public function categoryList(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategoryFilterType::class);
        $form->handleRequest($request);

        $courses = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $selectedCategory = $form->get('category')->getData();

            if ($selectedCategory) {
                $courses = $selectedCategory->getCourses();
            }
        } else {
            
            $courses = $entityManager->getRepository(Course::class)->findAll();
        }

        return $this->render('courses/index.html.twig', [
            'form' => $form->createView(),
            'courses' => $courses,
        ]);
    }
}
