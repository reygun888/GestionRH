<?php

namespace App\Controller;

use App\Entity\Absence;
use App\Form\AbsenceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'accueil')]
    public function index(Request $request): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Assurez-vous qu'un utilisateur est connecté
        if (!$user) {
            // Rediriger vers la page de connexion
            return $this->redirectToRoute('connexion');
        }

        // Création d'une nouvelle instance de l'entité Absence
        $absence = new Absence();

        // Associer l'employé connecté à l'absence
        $absence->setEmploye($user);

        // Création du formulaire en dehors de la condition de soumission du formulaire
        $form = $this->createForm(AbsenceType::class, $absence);

        // Gestion de la soumission du formulaire
        $form->handleRequest($request);

        // Vérifier si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrement en base de données
            $this->entityManager->persist($absence);
            $this->entityManager->flush();

            // Redirection ou autre logique après l'enregistrement
            return $this->redirectToRoute('accueil');
        }

        // Affichage du formulaire dans le template
        return $this->render('accueil/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/export-absences-json', name: 'export_absences_json')]
    public function exportAbsencesToJson(Request $request): Response
    {
        // Chemin où se trouve le fichier JSON des absences
        $filePath = $this->getParameter('kernel.project_dir') . '/public/absences.json';

        // Vérifier si le fichier JSON existe
        if (file_exists($filePath)) {
            // Lire le contenu du fichier JSON
            $absencesJsonString = file_get_contents($filePath);

            // Convertir le contenu JSON en tableau PHP
            $absences = json_decode($absencesJsonString, true);

            // Afficher les absences dans le template JSON
            return new JsonResponse($absences);
        } else {
            // Fichier JSON non trouvé, renvoyer une réponse d'erreur
            return new JsonResponse(['error' => 'Absences JSON file not found'], Response::HTTP_NOT_FOUND);
        }
    }

    #[Route('/api/events', name: 'api_events')]
    public function getEventsApi(EventRepository $eventRepository): JsonResponse
    {
        // Récupérer les événements depuis la base de données ou tout autre source
        $events = $eventRepository->findAll();

        // Convertir les événements en tableau associatif
        $eventsArray = [];
        foreach ($events as $event) {
            $eventsArray[] = [
                'id' => $event->getId(),
                'title' => $event->getTitle(),
                // Ajoutez d'autres champs d'événements au besoin
            ];
        }

        // Retourner les événements au format JSON
        return new JsonResponse($eventsArray);
    }
}
