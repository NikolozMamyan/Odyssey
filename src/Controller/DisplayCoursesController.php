<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DisplayCoursesController extends AbstractController
{
    #[Route('/courses', name: 'app_all_courses')]
    public function index(): Response
    {
        return $this->render('display_courses/index.html.twig', [
            'controller_name' => 'DisplayCoursesController',
        ]);
    }
}
