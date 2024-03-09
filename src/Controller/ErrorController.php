<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ErrorController extends AbstractController
{
    public function showError(\Throwable $exception): Response
    {
        // Gérer l'exception ici et afficher une page d'erreur personnalisée
        return $this->render('errors/error.html.twig', [
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
        ]);
    }
}