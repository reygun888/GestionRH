<?php

namespace App\Form;

use App\Entity\HeuresSup;
use App\Entity\Employe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HeuresSupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date')
            ->add('nombre_heures')
            ->add("approuve_par")
            ->add('employe', EntityType::class, [
                'class' => Employe::class,
                'choice_label' => 'nom', // ou tout autre champ que vous voulez afficher
                'disabled' => true, // Empêcher l'utilisateur de changer l'employé
            ])
            ->add("submit", SubmitType::class, [
                "label" => "Envoyer",
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => HeuresSup::class,
        ]);
    }
}
