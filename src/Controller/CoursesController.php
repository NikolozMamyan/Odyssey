<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Teacher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CoursesController extends AbstractController
{
    #[Route('/courses', name: 'app_courses')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // exemple of teacher and course for testing
        $teacher = new Teacher();
        $teacher->setName('John Doe');
        $teacher->setEmail('john.doe@gmail.com');

        $course = new Course();
        $course->setTitle('test course');
        $course->setDescription('test description');
        $course->setAuthor($teacher);
        $course->setContent('test content');

        $entityManager->persist($teacher);
        $entityManager->persist($course);
        $entityManager->flush();


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
}
