<?php

namespace App\Controller;

use App\Entity\HeuresSup;
use App\Form\HeuresSupType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HeuresSupController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/heuresSup', name: 'heuresSup')]
    public function index(Request $request): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();
    
        // Assurez-vous qu'un utilisateur est connecté
        if (!$user) {
            // Rediriger vers la page de connexion
            return $this->redirectToRoute('connexion');
        }
    
        // Création d'une nouvelle instance de l'entité HeuresSup
        $heuresSup = new HeuresSup();
    
        // Associer l'employé connecté à l'HeuresSup
        $heuresSup->setEmploye($user);
    
        // Création du formulaire
        $form = $this->createForm(HeuresSupType::class, $heuresSup);
    
        // Gestion de la soumission du formulaire
        $form->handleRequest($request);
    
        // Vérifier si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrement en base de données
            $this->entityManager->persist($heuresSup);
            $this->entityManager->flush();
    
            // Redirection ou autre logique après l'enregistrement
            return $this->redirectToRoute('heuresSup');
        }
    
        // Affichage du formulaire dans le template
        return $this->render('heures_sup/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
