<?php

namespace App\Controller;

use App\Repository\CourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class SearchController extends AbstractController
{
    

    #[Route('/search', name: 'app_search')]
    public function searchBar(): Response
    {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('handleSearch'))
            ->add('query', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez un mot-clé'
                ]
            ])
            ->add('recherche', SubmitType::class, [
                'attr' => [
                    'class' => 'sk-btn mt-3'
                ]
            ])
            ->getForm();

        return $this->render('search/searchBar.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    #[Route('/handleSearch', name: 'handleSearch')]
    public function handleSearch(Request $request, CourseRepository $repo): Response
    {
        $notes = $repo->getAverageNotes();
        
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('handleSearch'))
            ->add('query', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez un mot-clé'
                ]
            ])
            ->add('recherche', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-outline-secondary'
                ]
                ])->getForm();

        $form->handleRequest($request);

        $query = $form->getData()['query']; //$request->request->get('form')['query'] ?? '';

        $courses = [];
    
        if ($query) {
            $courses = $repo->findCourseByName($query);
        }
      
        return $this->render('search/index.html.twig', [
            'courses' => $courses,
            'notes' =>  $notes
        ]);
    }
    
}
