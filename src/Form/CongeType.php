<?php

namespace App\Form;

use App\Entity\Conge;
use App\Entity\Employe;
use Symfony\Component\Form\AbstractType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class CongeType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
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
                    "class"=> "mt-3 ps-2"
                ],
            ])
            ->add('dateFinAt',DateType::class,[
                "label" => "Au",
                "label_attr" => ["class" => "me-3"],
                "attr" => [
                    "class"=> "mt-3 ps-2"
                ],
            ])
            ->add("type_Conge", ChoiceType::class,[
                "label" => "Type de congé",
                "choices" => [
                    "Congé payé" => "Congé payé",
                    "Congé maternité" => "Congé maternité",
                    "Congé maladie" => "Congé maladie",
                    "Congé parental" => "Congé parental",
                ],
                "label_attr" => ["class" => "me-2"],
                "attr" => [
                    "class"=> "mt-3 p-1"
                ],
            ])
            ->add("submit", SubmitType::class, [
                "label" => "Envoyer",
                "attr" => [
                    "class"=> "btn btn-primary mt-3"
                ],
            ]);
            if ($isAdmin || $isManager) {
                $builder
                ->add('employe', EntityType::class, [
                    'class' => Employe::class,
                    'choice_label' => 'nom', // ou tout autre champ que vous voulez afficher
                    'disabled' => !($isAdmin || $isManager),
                    "label_attr" => ["class" => "me-3"],
                    "attr" => [
                        "class"=> "mt-3"
                    ],
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
            'data_class' => Conge::class,
        ]);
    }
}
