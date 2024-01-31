<?php

namespace App\Controller;

use App\Entity\Absence;
use App\Form\AbsenceType;
use App\Repository\AbsenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    private $entityManager;
    private $absenceRepository;

    public function __construct(EntityManagerInterface $entityManager, AbsenceRepository $absenceRepository)
    {
        $this->entityManager = $entityManager;
        $this->absenceRepository = $absenceRepository;
    }

    #[Route('/', name: 'accueil')]
    public function index(Request $request): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Vérifier si l'utilisateur est connecté
        if (!$user) {
            return $this->redirectToRoute('connexion');
        }

        // Créer une nouvelle instance d'absence et associer l'employé connecté
        $absence = new Absence();
        $absence->setEmploye($user);

        // Créer le formulaire d'absence
        $form = $this->createForm(AbsenceType::class, $absence);
        $form->handleRequest($request);

        // Traiter la soumission du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($absence);
            $this->entityManager->flush();

            return $this->redirectToRoute('accueil');
        }

        // Récupérer les absences de l'utilisateur
        $absences = $this->absenceRepository->findBy([
            "employe" => $user,
        ]);

        // Récupérer les absences pour le mois actuel
        $currentYear = date('Y');
        $currentMonth = date('m');
        $currentAbsences = $this->absenceRepository->findAbsencesForMonth($currentYear, $currentMonth);

        // Récupérer toutes les absences
        $allAbsences = $this->absenceRepository->findAll();

        // Convertir les données d'absence en tableau associatif
        // Encodez les données d'absence en JSON
        $absencesArray = [];
        foreach ($allAbsences as $absence) {
            // Récupérer l'employé associé à l'absence
            $employe = $absence->getEmploye();
            if ($employe !== null) {
                $absencesArray[] = [
                    'dateDebutAt' => $absence->getDateDebutAt()->format('Y-m-d'),
                    'dateFinAt' => $absence->getDateFinAt()->format('Y-m-d'),
                    'statut' => $absence->getStatut(),
                    "motif" => $absence->getMotif(),
                    "employe" => [
                        'nom' => $employe->getNom(),
                        // Ajoutez d'autres champs d'employé si nécessaire
                    ]
                    // Ajoutez d'autres champs d'absence si nécessaire
                ];
            } else {
                // Traitez le cas où l'employé est null
            }
        }

        $absencesJson = json_encode($absencesArray);

        // Rendre la vue avec les données nécessaires
        return $this->render('accueil/index.html.twig', [
            'form' => $form->createView(),
            "absences" => $absences,
            "currentAbsences" => $currentAbsences,
            'absencesData' => $absencesJson  // Transmettez les données JSON à la vue
        ]);
    }
}
