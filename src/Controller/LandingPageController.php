<?php

namespace App\Controller;

use App\Service\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class LandingPageController extends AbstractController
{

    /**
     * Display home page of website
     *
     * @return Response
     */
    #[Route('/', name: 'app_landing_page')]
    public function index(): Response
    {
        return $this->render('landing_page/index.html.twig');
    }

    /**
     * Send a mail to admin from contact form
     *
     * @param MailerService $mailerService
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return Response
     */
    #[Route('/contact', name: 'app_contact', methods: ['POST'])]
    public function contact(MailerService $mailerService, Request $request, ValidatorInterface $validator): Response
    {
        // recover data from form
        $data['name'] = $request->request->get('name');
        $data['email'] = $request->request->get('email');
        $data['message'] = $request->get('message');


        // Call the mailer service
        $message = $mailerService->sendMail($data);

        // display message
        if ($message === MailerService::SUCCESS_MESSAGE) {
            $this->addFlash('success', $message);
        } else {
            $this->addFlash('danger', $message);
        }

        return $this->redirectToRoute('app_landing_page');
    }

    /**
     * Display legals information
     *
     * @return Response
     */
    #[Route('/legals', name: 'app_legals_page')]
    public function showLegals(): Response
    {
        return $this->render('legals/legal_information.html.twig');
    }

    /**
     * Display information about us
     *
     * @return Response
     */
    #[Route('/about-us', name: 'app_about_us')]
    public function aboutUs(): Response
    {
        return $this->render('about_us/about_us.html.twig');
    }

}
