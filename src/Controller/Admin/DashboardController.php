<?php

namespace App\Controller\Admin;

use App\Entity\CollectionCategory;
use App\Entity\Item;
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
        yield MenuItem::linkToCrud('User', 'fas fa-user', User::class);
        yield MenuItem::linkToRoute('Home', 'fa fa-sign-out', 'app_main');

        yield MenuItem::section('Collections');
        yield MenuItem::linkToCrud('Categories', 'fa-brands fa-readme', CollectionCategory::class);
        yield MenuItem::linkToCrud('Collections', 'fas fa-list', ItemCollection::class);
        yield MenuItem::linkToCrud('Items', 'fa-solid fa-vr-cardboard', Item::class);


    }
}
