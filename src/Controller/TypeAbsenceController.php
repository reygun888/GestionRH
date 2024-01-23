<?php

namespace App\Controller;

use App\Entity\TypeAbsence;
use App\Form\TypeAbsenceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TypeAbsenceController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/motif', name: "motif")]
    public function index(Request $request): Response
    {
        // Création d'une nouvelle instance de l'entité TypeAbsence
        $typeAbsence = new TypeAbsence();

        // Création du formulaire
        $form = $this->createForm(TypeAbsenceType::class, $typeAbsence);

        // Gestion de la soumission du formulaire
        $form->handleRequest($request);

        // Vérifier si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrement en base de données
            $this->entityManager->persist($typeAbsence);
            $this->entityManager->flush();

            // Redirection ou autre logique après l'enregistrement
            return $this->redirectToRoute('accueil'); // Remplacez 'quelque_part' par le nom de votre route
        }

        // Affichage du formulaire dans le template
        return $this->render('type_absence/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
