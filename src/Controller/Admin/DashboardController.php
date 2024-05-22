<?php

namespace App\Controller\Admin;

use App\Entity\CollectionCategory;
use App\Entity\ItemCollection;
use App\Entity\User;
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
         return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Collections App');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
         yield MenuItem::linkToCrud('User', 'fas fa-user', User::class);

         yield MenuItem::section('Collections');
         yield MenuItem::linkToCrud('Categories', 'fas fa-medium', CollectionCategory::class);
        yield MenuItem::linkToCrud('Collections', 'fas fa-list', ItemCollection::class);

    }
}
