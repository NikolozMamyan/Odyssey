<?php

namespace App\Controller;

use App\Entity\Course;
use App\Form\CategoryFilterType;
use Doctrine\ORM\EntityManagerInterface;
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

        $courses = $entityManager->getRepository(Course::class)->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->get('name')->getData();
            if ($category) {
                $courses = $category->getCourses();
            }
        }

        return $this->render('courses/index.html.twig', [
            'form' => $form->createView(),
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


}
