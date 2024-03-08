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
use Symfony\Component\String\Slugger\SluggerInterface;


class CoursesController extends AbstractController
{
    /**
     * Display the course list and form search
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


    /**
     * Create a new Course
     *
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param MailerService $mailer
     * @param SluggerInterface $slugger
     * @return Response
     */
    #[Route('/courses/create', name: 'app_course_create')]
    public function create(EntityManagerInterface $entityManager,
                           Request $request,
                           MailerService $mailer,
                           SluggerInterface $slugger): Response
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

            $slug = $slugger->slug($course->getTitle());
            $course->setSlug($slug);

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

            return $this->redirectToRoute('app_user');
        }

        return $this->render('courses/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Show détails of a course
     *
     * @param string $slug
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/courses/{slug}', name: 'app_course_show')]
    public function show(string $slug, EntityManagerInterface $entityManager): Response
    {
        $course = $entityManager->getRepository(Course::class)->findOneBy(['slug' => $slug]);

        if (!$course) {
            throw $this->createNotFoundException('No course found for this slug');
        }

        return $this->render('courses/show.html.twig', [
            'course' => $course,
        ]);
    }

    /**
     * Update a course
     *
     * @param string $slug
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param SluggerInterface $slugger
     * @return Response
     */
    #[Route('/courses/edit/{slug}', name: 'app_course_edit')]
    public function edit(string $slug,
                         EntityManagerInterface $entityManager,
                         Request $request,
                         SluggerInterface $slugger): Response
    {
        $course = $entityManager->getRepository(Course::class)->findOneBy(['slug' => $slug]);
        $categories = $entityManager->getRepository(Category::class)->findAll();

        $form = $this->createForm(CourseType::class, $course, [
            'categories' => $categories,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $course->setSlug($slugger->slug($course->getTitle()));

            $entityManager->flush();

            return $this->redirectToRoute('app_course_show', ['slug' => $course->getSlug()]);
        }

        return $this->render('courses/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Delete a course
     *
     * @param EntityManagerInterface $entityManager
     * @param int $id
     * @return RedirectResponse
     */
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


    /**
     * Add course in the collection of courses in User Entity (add watchlist)
     *
     * @param string $slug
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse
     */
    #[Route('/courses/participate/{slug}', name: 'app_course_participate')]
    public function participateCourses(string $slug, EntityManagerInterface $entityManager): RedirectResponse
    {
        $user = $this->getUser();
        $course = $entityManager->getRepository(Course::class)->findOneBy(['slug' => $slug]);

        $user->addParticipateCourse($course);

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_course_show', ['slug' => $course->getSlug()]);
    }


    /**
     * Remove course in the collection of courses in User Entity (remove watchlist)
     *
     * @param int $id
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse
     */
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
