<?php

namespace App\Form;

use App\Entity\Absence;
use App\Entity\Employe;
use Symfony\Component\Form\AbstractType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints\Callback;


class AbsenceType extends AbstractType
{
    private $security;
    private $entityManager;

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
    }
   
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isAdmin = $this->security->isGranted('ROLE_ADMIN');
        $isManager = $this->security->isGranted('ROLE_MANAGER');

        $builder
            ->add('dateDebutAt',DateType::class,[
                "label" => "Du",
                "label_attr" => ["class" => "me-3"],
                "attr" => [
                    "class"=> "mt-3"
                ],
                "data" => new \DateTime(),  // Définir la date actuelle comme date par défaut,
            ])
            ->add('dateFinAt',DateType::class,[
                "label" => "Au",
                "label_attr" => ["class" => "me-3"],
                "attr" => [
                    "class"=> "mt-3"
                ],
                "data" => new \DateTime(),  // Définir la date actuelle comme date par défaut
            ] )
            ->add('motif', TypeTextType::class,[
                "label" => false,
                "attr" => [
                    "placeholder" => "Motif",
                    "class" => "motif form-control ps-1 mt-3"
                ],
            ])
            ->add("submit", SubmitType::class, [
                "label" => "Envoyer",
                "attr" => [
                    "class"=> "btn btn-primary mt-3"
                ], 
            ]);
            if ($isAdmin || $isManager){
                $builder    
            ->add('employe', EntityType::class, [
                'class' => Employe::class,
                'choice_label' => 'nom', // ou tout autre champ que vous voulez afficher
                "label_attr" => ["class" => "me-3"],
                "attr" => [
                    "class"=> "mt-2"
                ],
                'disabled' => !($isAdmin || $isManager), // Désactivé si l'utilisateur n'est pas un administrateur
            ])
            
            ->add("statut", ChoiceType::class, [
                "label" => "Valider",
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                "required" => false,
                "label_attr" => ["class" => "me-3"],
                "attr" => [
                    "class"=> "statut mt-2"
                ],
                'disabled' => !($isAdmin || $isManager), // Désactivé si l'utilisateur n'est pas un administrateur
            ]);
        }
        $builder
        ->add("submit", SubmitType::class, [
            "label" => "Envoyer",
            "attr" => [
                "class"=> "btn btn-primary mt-3"
            ], 
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Absence::class,
        ]);
    }
}

    // public function configureOptions(OptionsResolver $resolver): void
    // {
    //     $resolver->setDefaults([
    //         'data_class' => Absence::class,
    //         'constraints' => [
    //             new Callback([$this, 'validateDateRange'])
    //         ]
    //     ]);
    // }

//     public function validateDateRange(Absence $absence, ExecutionContextInterface $context): void
//     {
//         $repository = $this->entityManager->getRepository(Absence::class);
//         $existingAbsence = $repository->findOneBy([
//             'employe' => $absence->getEmploye(),
//             'dateDebutAt' => $absence->getDateDebutAt(),
//             'dateFinAt' => $absence->getDateFinAt(),
//         ]);
        
//         if ($existingAbsence && $existingAbsence->getId() !== $absence->getId()) {
//             $context->buildViolation('Une absence pour ces dates existe déjà.')
//                 ->addViolation();
//         }
//     }
// }


