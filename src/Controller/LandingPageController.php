<?php

namespace App\Controller;

use App\Service\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class LandingPageController extends AbstractController
{
    #[Route('/', name: 'app_landing_page')]
    public function index(): Response
    {
        return $this->render('landing_page/index.html.twig', [
            'controller_name' => 'LandingPageController',
        ]);
    }

    #[Route('/contact', name: 'app_contact', methods:['POST'])]
    public function contact(MailerService $mailerService, Request $request, ValidatorInterface $validator): Response
    {
        // recover data from form
        $data['name'] = $request->request->get('name');
        $data['email'] = $request->request->get('email');
        $data['message'] = $request->get('message');


        // Call the mailer service
        $message = $mailerService->sendMail($data);

        if ($message === 'Votre message a bien été envoyé' ) {
            $this->addFlash('success', $message);
        } else {
            $this->addFlash('danger', $message);
        }

        return $this->redirectToRoute('app_landing_page');
    }

}
