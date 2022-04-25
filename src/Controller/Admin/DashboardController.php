<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private AdminUrlGenerator $adminUrlGenerator
    ) {
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator
            ->setController(ArticleCrudController::class)
            ->generateUrl();
        return $this->redirect(($url));

    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('SymfonySite');
    }

    public function configureMenuItems(): iterable
    {
        //yield MenuItem::section('Blog');
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Categories', 'fas fa-list-alt', Category::class);
        yield MenuItem::linkToCrud('Articles', 'far fa-newspaper', Article::class);
        yield MenuItem::linkToCrud('User', 'fas fa-users', User::class);
    }
}
