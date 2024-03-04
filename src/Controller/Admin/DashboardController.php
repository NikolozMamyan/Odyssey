<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Course;
use App\Entity\Note;
use App\Entity\Role;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
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
            MenuItem::linkToCrud('Créer un utilisateur', 'fas fa-plus', User::class)->setAction(Crud::PAGE_NEW),
        ]);

        yield MenuItem::section('Roles');
        yield MenuItem::subMenu('Rôles', 'fas fa-bullhorn')->setSubItems([
            MenuItem::linkToCrud('Voir les Rôles', 'fas fa-eye', Role::class),
            MenuItem::linkToCrud('Créer un rôle', 'fas fa-plus', Role::class)->setAction(Crud::PAGE_NEW),
        ]);

        yield MenuItem::section('Cours');
        yield MenuItem::subMenu('Cours', 'fas fa-file')->setSubItems([
            MenuItem::linkToCrud('Voir les cours', 'fas fa-eye', Course::class),
        ]);
        yield MenuItem::section('Catégories');
        yield MenuItem::subMenu('Catégories', 'fas fa-circle-info')->setSubItems([
            MenuItem::linkToCrud('Voir les catégories', 'fas fa-eye', Category::class),
            MenuItem::linkToCrud('Créer une catégorie', 'fas fa-plus', Category::class)->setAction(Crud::PAGE_NEW),
        ]);


    }
}
