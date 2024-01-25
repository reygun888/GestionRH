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

class AbsenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDebutAt')
            ->add('dateFinAt')
            ->add('statut', CheckboxType::class, [
                'label' => 'Statut',
                'required' => false, // Pour permettre la case à cocher non obligatoire
            ])
            ->add('employe', EntityType::class, [
                'class' => Employe::class,
                'choice_label' => 'nom', // ou tout autre champ que vous voulez afficher
                'disabled' => true, // Empêcher l'utilisateur de changer l'employé
            ])
            ->add('motif')
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
