<?php

namespace App\Form;

use App\Entity\Absence;
use App\Entity\Employe;
use App\Entity\TypeAbsence;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AbsenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDebutAt')
            ->add('dateFinAt')
            ->add('statut')
            ->add('employe', EntityType::class, [
                'class' => Employe::class,
'choice_label' => 'id',
            ])
            ->add("motif")
            ->add("submit",SubmitType::class,[
                "label" => "Envoyer",
            ])    
        
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Absence::class,
        ]);
    }
}
