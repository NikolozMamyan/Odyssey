<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Course;
use App\Entity\Note;
use App\Entity\Role;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Odyssey');
    }

    public function configureAssets(): Assets
    {
        //todo: implement css file
        return Assets::new()->addCssFile('css/admin.css');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        //yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);

        yield MenuItem::section('Utilisateurs');
        yield MenuItem::subMenu('Utilisateurs', 'fas fa-user')->setSubItems([
            MenuItem::linkToCrud('Voir Utilisateurs', 'fas fa-eye', User::class),
        ]);

        yield MenuItem::section('Roles');
        yield MenuItem::subMenu('Roles', 'fas fa-user')->setSubItems([
            MenuItem::linkToCrud('Voir les Roles', 'fas fa-eye', Role::class),
        ]);
        yield MenuItem::section('Cours');
        yield MenuItem::subMenu('Cours', 'fa-brands fa-discourse')->setSubItems([
            MenuItem::linkToCrud('Voir les cours', 'fas fa-eye', Course::class),
        ]);
        yield MenuItem::section('Catégories');
        yield MenuItem::subMenu('Catégories', 'fas fa-user')->setSubItems([
            MenuItem::linkToCrud('Voir les catégories', 'fas fa-eye', Category::class),
        ]);
        yield MenuItem::section('Notes');
        yield MenuItem::subMenu('Notes', 'fas fa-user')->setSubItems([
            MenuItem::linkToCrud('Voir les notes', 'fas fa-eye', Note::class),
        ]);

    }
}
