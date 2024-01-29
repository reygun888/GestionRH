<?php

namespace App\Form;

use App\Entity\Absence;
use App\Entity\Employe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;

class AbsenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDebutAt',DateTimeType::class,[
                "label" => false
            ])
            ->add('dateFinAt',DateTimeType::class,[
                "label" => false
            ] )
            ->add('employe', EntityType::class, [
                'class' => Employe::class,
                'choice_label' => 'nom', // ou tout autre champ que vous voulez afficher
                'disabled' => true, // Empêcher l'utilisateur de changer l'employé
            ])
            ->add("statut", CheckboxType::class,[
                "label" => false
            ])
            ->add('motif', TypeTextType::class,[
                "label" => false
            ])
            ->add("submit", SubmitType::class, [
                "label" => "Envoyer",
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Absence::class,
        ]);
    }
}
