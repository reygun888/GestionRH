<?php

namespace App\Controller;

use App\Form\ChangeMdpType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ChangeMdpController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/change/mdp', name: 'changeMdp')]
    public function index(Request $request, UserPasswordHasherInterface $mdpHasher): Response
    {
        
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute("connexion");
        }

        // Instance du formulaire ChangeMdpType
        $form = $this->createForm(ChangeMdpType::class, $user);

        // Validation du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nouveauMdp = $form->get('nouveauMDP')->getData();
            $confirmationMdp = $form->get('nouveauMDP')->getData(); // Utiliser le même champ pour obtenir la confirmation
        
            if (empty($nouveauMdp)) {
                $this->addFlash("error", "Le nouveau mot de passe ne peut pas être vide");
            } elseif ($nouveauMdp !== $confirmationMdp) {
                $this->addFlash("error", "La confirmation du mot de passe ne correspond pas");
            } else {
                // Validation et hashage du nouveau MDP
                $hashedMDP = $mdpHasher->hashPassword($user, $nouveauMdp);
        
                // Mise à jour du nouveau mot de passe de l'utilisateur
                $user->setPassword($hashedMDP);
        
                // Si c'est la première connexion, marquer firstLogin comme false
                if ($user->isFirstLogin()) {
                    $user->setFirstLogin(false);
                }
        
                // Enregistrement des changements en BDD
                $this->entityManager->persist($user);
                $this->entityManager->flush();
        
                // Message de validation du changement de MDP
                $this->addFlash("success", "Le mot de passe a été changé avec succès");
        
                return $this->redirectToRoute("accueil");
            }
        }

        return $this->render('change_mdp/index.html.twig', [
            "form" => $form->createView()
        ]);
    }
}
