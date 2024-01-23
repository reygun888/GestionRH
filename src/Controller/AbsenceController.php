<?php

namespace App\Controller;

use App\Entity\Absence;
use App\Form\AbsenceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AbsenceController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/absence', name: 'absence')]
    public function index(Request $request): Response
    {
        // Création d'une nouvelle instance de l'entité Absence
        $absence = new Absence();

        // Création du formulaire
        $form = $this->createForm(AbsenceType::class, $absence);

        // Gestion de la soumission du formulaire
        $form->handleRequest($request);

        // Vérifier si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrement en base de données
            $this->entityManager->persist($absence);
            $this->entityManager->flush();

            // Redirection ou autre logique après l'enregistrement
            return $this->redirectToRoute('accueil'); // Remplacez 'quelque_part' par le nom de votre route
        }

        // Affichage du formulaire dans le template
        return $this->render('absence/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
