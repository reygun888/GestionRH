<?php

namespace App\Controller\Admin;

use App\Entity\Employe;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class EmployeCrudController extends AbstractCrudController
{   

    public function verifRole (AuthorizationCheckerInterface $authorizationChecker){
        if(!$authorizationChecker -> isGranted("ROLE_ADMIN")){
            throw $this->createAccessDeniedException("Accès réfusé.");
        }
    }

    private UserPasswordHasherInterface $passwordEncoder;

    public function __construct(UserPasswordHasherInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public static function getEntityFqcn(): string
    {
        return Employe::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Employe')
            ->setEntityLabelInPlural('Employes');
    }

    public function configureFields(string $pageName): iterable
    {

        $fields = [
            TextField::new('nom'),
            TextField::new('prenom'),
            TextField::new("email"),
            TextField::new("departement"),
            TextField::new("poste"),
            ArrayField::new("roles", "Rôles"),
            BooleanField::new("first_login", "Première connexion ?"),
        ];

        // Lorsque vous créez un nouvel employé, ajoutez le champ du mot de passe
        if ($pageName === Crud::PAGE_NEW) {
            $fields[] = TextField::new('plainPassword', 'Mot de passe');
        }

        return $fields;
    }

    // Appelé avant la persistance de l'entité Employe
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // Vérifiez si l'entité est une instance d'Employe
        if ($entityInstance instanceof Employe) {
            // Vérifiez si un nouveau mot de passe est défini
            if ($entityInstance->getPlainPassword() !== null) {
                // Hasher le mot de passe
                $hashedPassword = $this->passwordEncoder->hashPassword($entityInstance, $entityInstance->getPlainPassword());
                $entityInstance->setPassword($hashedPassword);

                // Réinitialisez le champ de mot de passe brut pour éviter de le persister tel quel en base de données
                $entityInstance->setPlainPassword(null);
            }
        }

        // Passez l'appel à la méthode parente pour effectuer la persistance réelle
        parent::persistEntity($entityManager, $entityInstance);
    }

    // Appelé avant la mise à jour de l'entité Employe
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // Vérifiez si l'entité est une instance d'Employe
        if ($entityInstance instanceof Employe) {
            // Vérifiez si un nouveau mot de passe est défini
            if ($entityInstance->getPlainPassword() !== null) {
                // Hasher le mot de passe
                $hashedPassword = $this->passwordEncoder->hashPassword($entityInstance, $entityInstance->getPlainPassword());
                $entityInstance->setPassword($hashedPassword);

                // Réinitialisez le champ de mot de passe brut pour éviter de le persister tel quel en base de données
                $entityInstance->setPlainPassword(null);
            }
        }

        // Passez l'appel à la méthode parente pour effectuer la mise à jour réelle
        parent::updateEntity($entityManager, $entityInstance);
    }
}
