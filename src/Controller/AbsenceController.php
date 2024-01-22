<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AbsenceController extends AbstractController
{
    #[Route('/absence', name: 'absence')]
    public function index(): Response
    {
        return $this->render('absence/index.html.twig', [
            'controller_name' => 'AbsenceController',
        ]);
    }
}
