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
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {

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

        yield MenuItem::linkToDashboard('Accueil', 'fa fa-home');
        yield MenuItem::linkToUrl('Site Odyssey', 'fa fa-reply-all', '/');

        yield MenuItem::section('Utilisateurs');
        yield MenuItem::subMenu('Utilisateurs', 'fas fa-user')->setSubItems([
            MenuItem::linkToCrud('Voir Utilisateurs', 'fas fa-eye', User::class),
        ]);

        yield MenuItem::section('Roles');
        yield MenuItem::subMenu('Roles', 'fas fa-user')->setSubItems([
            MenuItem::linkToCrud('Voir les Roles', 'fas fa-eye', Role::class),
        ]);
        yield MenuItem::section('Cours');
        yield MenuItem::subMenu('Cours', 'fas fa-bullhorn')->setSubItems([
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
