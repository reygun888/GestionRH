<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/inscription', name: 'inscription')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $emi): Response
    {
        $this->emi = $emi;

        $user = new Employe();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Vérifier via l'email si l'utilisateur existe ou non 

            $userExist = $this->emi->getRepository(Employe::class)->findOneBy(["email" => $user->getEmail()]);

            if (!$userExist) {
                // encoder le mot de passe
                $encodedPassword = $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                );

                $user->setPassword($encodedPassword);

                $emi->persist($user);
                $emi->flush();
                // effectuer toute autre opération nécessaire, comme l'envoi d'un e-mail

                $this->addFlash("success", "Utilisateur créé avec succès");

                return $this->redirectToRoute('connexion');
            }else{
                $this->addFlash("error","Cette adresse mail est déjà utilisée");
            }
        }
        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
