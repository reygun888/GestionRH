<?php
namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Http\Event\LogoutEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class LogoutListener
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function onLogoutSuccess(LogoutEvent $event)
    {
        // Crée une réponse de redirection vers la page de connexion
        $response = new RedirectResponse($this->urlGenerator->generate('connexion'));

        // Ajoute les en-têtes de cache pour désactiver le cache
        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');

        // Définit la réponse dans l'événement de déconnexion
        $event->setResponse($response);
    }
}
