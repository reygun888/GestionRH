<?php

namespace App\Controller;

use App\Entity\Conge;
use App\Form\CongeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CongeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/conge', name: 'conge')]
    public function index(Request $request): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();
    
        // Assurez-vous qu'un utilisateur est connecté
        if (!$user) {
            // Rediriger vers la page de connexion
            return $this->redirectToRoute('connexion');
        }
    
        // Création d'une nouvelle instance de l'entité Conge
        $conge = new Conge();
    
        // Associer l'employé connecté à l'Conge
        $conge->setEmploye($user);
    
        // Création du formulaire
        $form = $this->createForm(CongeType::class, $conge);
    
        // Gestion de la soumission du formulaire
        $form->handleRequest($request);
    
        // Vérifier si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrement en base de données
            $this->entityManager->persist($conge);
            $this->entityManager->flush();
    
            // Redirection ou autre logique après l'enregistrement
            return $this->redirectToRoute('conge');
        }
    
        // Affichage du formulaire dans le template
        return $this->render('conge/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
