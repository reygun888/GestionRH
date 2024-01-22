<?php

namespace App\Controller\Admin;

use App\Entity\Absence;
use App\Entity\Conge;
use App\Entity\Employe;
use App\Entity\HeuresSup;
use App\Entity\Rapport;
use App\Entity\TypeAbsence;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;


class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {

        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(EmployeCrudController::class)->generateUrl());

        
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('GestionRh');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

  
        yield MenuItem::linkToCrud("Absence", "fa fa-user-xmark", Absence::class);
        yield MenuItem::linkToCrud("Conge", "fa fa-person-through-window", Conge::class);
        yield MenuItem::linkToCrud("Employe", "fa fa-user",Employe::class);
        yield MenuItem::linkToCrud("HeuresSup","fa fa-clock",HeuresSup::class);
        yield MenuItem::linkToCrud("Rapport", "fa fa-file", Rapport::class);
        yield MenuItem::linkToCrud("Motif d'absence", "fa fa-file-signature",TypeAbsence::class);

    }
}
