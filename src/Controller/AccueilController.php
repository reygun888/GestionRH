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
                // Récupérer les données d'absence depuis votre repository AbsenceRepository
        $absences = $this->absenceRepository->findAll();

        // Convertir les données d'absence en tableau associatif
        $absencesArray = [];
        foreach ($absences as $absence) {
            $absencesArray[] = [
                'dateDebutAt' => $absence->getDateDebutAt()->format('Y-m-d'),
                'dateFinAt' => $absence->getDateFinAt()->format('Y-m-d'),
                'statut' => $absence->getStatut(),
                // Ajoutez d'autres champs d'absence si nécessaire
            ];
        }

        // Transmettre les données d'absence à la vue Twig
        return $this->render('accueil/index.html.twig', [
            'absencesData' => json_encode($absencesArray)
        ]);
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('connexion');
        }

        $absence = new Absence();
        $absence->setEmploye($user);

        $form = $this->createForm(AbsenceType::class, $absence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($absence);
            $this->entityManager->flush();

            return $this->redirectToRoute('accueil');
        }

        $absences = $this->absenceRepository->findBy([
            "employe" => $user,
        ]);

        // Récupérer les absences pour le mois actuel
        $currentYear = date('Y');
        $currentMonth = date('m');
        $currentAbsences = $this->absenceRepository->findAbsencesForMonth($currentYear, $currentMonth);

        

        return $this->render('accueil/index.html.twig', [
            'form' => $form->createView(),
            "absences" => $absences,
            "currentAbsences" => $currentAbsences // Ajoutez les absences pour le mois actuel
        ]);
    }
    
    #[Route("/get_absences", name:"get_absences")]
    public function getAbsencesForMonth(Request $request): JsonResponse
    {
        // Récupérer la date du mois spécifié depuis la requête
        $date = $request->query->get('date');

        // Analyser la date pour obtenir le mois et l'année
        $year = date('Y', strtotime($date));
        $month = date('m', strtotime($date));

        // Récupérer les absences pour le mois spécifié depuis le référentiel
        $absences = $this->absenceRepository->findAbsencesForMonth($year, $month);

        // Convertir les absences en format JSON et les renvoyer
        return $this->json($absences);
    }


}

