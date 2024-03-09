<?php

namespace App\Controller;

use App\Entity\Conge;
use App\Entity\Absence;
use App\Form\CongeType;
use App\Entity\HeuresSup;
use App\Form\AbsenceType;
use App\Form\HeuresSupType;
use App\Repository\CongeRepository;
use App\Repository\AbsenceRepository;
use App\Repository\HeuresSupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class AccueilController extends AbstractController
{
    private $entityManager;
    private $absenceRepository;
    private $congeRepository;
    private $heuresSupRepository;
    private $authorizationChecker;

    public function __construct(
        EntityManagerInterface $entityManager,
        AbsenceRepository $absenceRepository,
        CongeRepository $congeRepository,
        HeuresSupRepository $heuresSupRepository,
        AuthorizationCheckerInterface $authorizationChecker,
    ) {
        $this->entityManager = $entityManager;
        $this->absenceRepository = $absenceRepository;
        $this->congeRepository = $congeRepository;
        $this->heuresSupRepository = $heuresSupRepository;
        $this->authorizationChecker = $authorizationChecker;
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

    // Récupérer le rôle de l'utilisateur
    $userRole = $user->getRoles()[0]; // Supposons que l'utilisateur a un seul rôle pour cet exemple

    // Initialiser les variables pour stocker les données
    $absences = [];
    $conges = [];
    $heuresSup = [];

    // Si l'utilisateur est un administrateur, récupérer toutes les absences et tous les congés
    if ($this->authorizationChecker->isGranted('ROLE_ADMIN') || $this->authorizationChecker->isGranted('ROLE_MANAGER')) {
        $allAbsences = $this->absenceRepository->findAll();
        $allConges = $this->congeRepository->findAll();
        $allHeuresSup = $this->heuresSupRepository->findAll();
    } else {
        // Sinon, récupérer les absences et les congés spécifiques à cet utilisateur
        $allAbsences = $this->absenceRepository->findBy(['employe' => $user]);
        $allConges = $this->congeRepository->findBy(['employe' => $user]);
        $allHeuresSup = $this->heuresSupRepository->findBy(['employe' => $user]);
    }

        // Créer les différentes instances et associer l'employé connecté
        $absence = new Absence();
        $absence->setEmploye($user);

        $conge = new Conge();
        $conge->setEmploye($user);

        $heuresSup = new HeuresSup();
        $heuresSup->setDate(new \DateTime());
        $heuresSup->setEmploye($user);

        // Créer le formulaire d'absence
        $formAbsence = $this->createForm(AbsenceType::class, $absence);
        $formAbsence->handleRequest($request);

        // Créer le formulaire congé
        $formConge = $this->createForm(CongeType::class, $conge);
        $formConge->handleRequest($request);

        // Créer le formulaire heures Sup
        $formHeuresSup = $this->createForm(HeuresSupType::class, $heuresSup);
        $formHeuresSup->handleRequest($request);
        
        
        // Traiter la soumission du formulaire de Congé
        if ($formConge->isSubmitted() && $formConge->isValid()) {
            $congeRepository = $this -> entityManager->getRepository(Conge::class);
            if($congeRepository->isDuplicateConge($conge)){
                $this->addflash('error','Un congé pour cette période existe déja');
            }else{
            $this->entityManager->persist($conge);
            $this->entityManager->flush();
            $this->addFlash('succes','Votre demande de congé est bien créée');
            }

            return $this->redirectToRoute("accueil");
        }

        // Traiter la soumission du formulaire d'Heures Sup
        if ($formHeuresSup->isSubmitted() && $formHeuresSup->isValid()) {
            $heuresSupRepository = $this -> entityManager->getRepository(HeuresSup::class);
            if($heuresSupRepository->isDuplicateHeureSup($heuresSup)){
                $this->addFlash('error', 'Une déclaration est deja présente pour se jour');
            }else{
            $this->entityManager->persist($heuresSup);
            $this->entityManager->flush();
            $this->addFlash('succes', 'Votre déclaration a bien été prise en compte');
            }

            return $this->redirectToRoute("accueil");
        }

        // Traiter la soumission du formulaire d'absence

        if ($formAbsence->isSubmitted() && $formAbsence->isValid()) {
            $absenceRepository = $this -> entityManager->getRepository(Absence::class);
            //Verifier d'il n'y a pas de doublons
            if($absenceRepository->isDuplicateAbsence($absence)){
                //Si il y a doublons
                $this->addFlash('error','Une absence pour cette période existe déjà');
            }else{
                // Enregistrement en base de données
            $this->entityManager->persist($absence);
            $this->entityManager->flush();
            //Envoi du message de succès
            $this->addFlash('succes','Votre demande d\'absences est bien créée');
            
            }
            // Redirection ou autre logique après l'enregistrement
            return $this->redirectToRoute('accueil');
        }

        // Récupérer les absences, les congés et les Heures sup pour le mois actuel
        $currentYear = date('Y');
        $currentMonth = date('m');
        $currentAbsences = $this->absenceRepository->findAbsencesForMonth($currentYear, $currentMonth);
        $currentConges = $this->congeRepository->findCongeForMonth($currentYear, $currentMonth);
        $currentHeuresSup = $this->heuresSupRepository->findHeuresSupForMonth($currentYear, $currentMonth);


        // Convertir les données de congé en tableau associatif et les encoder en JSON
        $congesArray = [];
        foreach ($allConges as $conge) {
            $employe = $conge->getEmploye();
            if ($employe !== null) {
                $congesArray[] = [
                    "dateDebutAt" => $conge->getDateDebutAt()->format("Y-m-d"),
                    "dateFinAt" => $conge->getDateFinAt()->format("Y-m-d"),
                    "typeConge" => $conge->getTypeConge(),
                    'statut' => $conge->getStatut(),
                    "employe" => [
                        "nom" => $employe->getNom(),
                        "prenom" => $employe->getPrenom(),
                        "id" => $employe->getId()
                    ]
                ];
            }
        }

        // Convertir les données d'heures supplémentaires en tableau associatif et les encoder en JSON
        $heuresSupArray = [];
        foreach ($allHeuresSup as $heuresSup) {
            $employe = $heuresSup->getEmploye();
            if ($employe !== null) {
                $heuresSupArray[] = [
                    'date' => $heuresSup->getDate()->format('Y-m-d'),
                    'heureDepart' => $heuresSup->getHeureDepart() ? $heuresSup->getHeureDepart()->format('H:i') : '',
                    "nbHeures" => $heuresSup->getNombreHeures(),
                    "employe" => [
                        "nom" => $employe->getNom(),
                        "prenom" => $employe->getPrenom(),
                        "id" => $employe->getId()
                    ],
                    "roles" => $employe->getRoles(),
                ];
            }
        }

        // Convertir les données d'absence en tableau associatif et les encoder en JSON
        $absencesArray = [];
        foreach ($allAbsences as $absence) {
            $employe = $absence->getEmploye();
            if ($employe !== null) {
                $absencesArray[] = [
                    'dateDebutAt' => $absence->getDateDebutAt()->format('Y-m-d'),
                    'dateFinAt' => $absence->getDateFinAt()->format('Y-m-d'),
                    'statut' => $absence->getStatut(),
                    "motif" => $absence->getMotif(),
                    "employe" => [
                        'nom' => $employe->getNom(),
                        "prenom" => $employe->getPrenom(),
                        "id" => $employe->getId()
                    ]
                ];
            }
        }

        // Convertir les tableaux associatifs en JSON
        $absencesJson = json_encode($absencesArray);
        $heuresSupJson = json_encode($heuresSupArray);
        $congesJson = json_encode($congesArray);

        // Rendre la vue avec les données nécessaires
        return $this->render('accueil/index.html.twig', [
            'formAbsence' => $formAbsence->createView(),
            "formHeuresSup" => $formHeuresSup->createView(),
            "formConge" => $formConge->createView(),
            "userRole" => $userRole,
            "absences" => $absences,
            "conges" => $conges,
            "heuresSup" => $heuresSup,
            "currentAbsences" => $currentAbsences,
            "currentConges" => $currentConges,
            "currentHeuresSup" => $currentHeuresSup,
            // Transmettez les données JSON à la vue
            'absencesData' => $absencesJson,
            "congeData" => $congesJson,
            "heuresSupData" => $heuresSupJson,
        ]);
    }
}
