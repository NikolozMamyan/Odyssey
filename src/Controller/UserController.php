<?php

namespace App\Controller;


use App\Repository\UserRepository;
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
        // dd($user);
        return $this->render('user/index.html.twig', [
            'user' => $user,
        ]);
    }



}
