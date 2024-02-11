<?php

namespace App\Controller;

use App\Form\CategoryFilterType;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(Request $request, CategoryRepository $categoryRepository): Response
    {
        $form = $this->createForm(CategoryFilterType::class);

        $form->handleRequest($request);

        $courses = [];

        if ($form->isSubmitted() && $form->isValid()) {
           
            $formData = $form->getData();

            
            $selectedCategory = $formData['categories'] ?? null;

           
            if ($selectedCategory) {
                $courses = $categoryRepository->findByCategory($selectedCategory);
            }
        }

        return $this->render('category/filter.html.twig', [
            'form' => $form->createView(),
            'courses' => $courses,
        ]);
    }
}
