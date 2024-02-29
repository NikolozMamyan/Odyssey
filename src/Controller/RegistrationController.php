<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request,
                             UserPasswordHasherInterface $userPasswordHasher,
                             EntityManagerInterface $entityManager): Response
    {

        if ($this->getUser()) {
            return $this->redirectToRoute('app_landing_page');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $user->setDateRegisterUser(new \DateTime);

            // check roles (student ou teacher)
            $isStudent = $form->get('student')->getData();

            if ($isStudent) {
                $user->setRoles(['ROLE_USER']);
                $role = $entityManager->getRepository(Role::class)->findOneBy(['typeRole' => 'student']);

            } else {

                $user->setRoles(['ROLE_TEACHER']);
                $role = $entityManager->getRepository(Role::class)->findOneBy(['typeRole' => 'teacher']);
            }
            $user->setRoleUser($role);

            $entityManager->persist($user);
            $entityManager->flush();

            // display message
            $this->addFlash('success', 'Votre compte a bien été crée.');

            return $this->redirectToRoute('app_landing_page');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
