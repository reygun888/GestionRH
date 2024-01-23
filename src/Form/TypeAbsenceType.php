<?php

namespace App\Form;

use App\Entity\Absence;
use App\Entity\TypeAbsence;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TypeAbsenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description')
            ->add('date_absence')
            ->add("submit",SubmitType::class,[
                "label" => "Envoyer",
            ])
/*             ->add('absence', EntityType::class, [
                'class' => Absence::class,
                'choice_label' => 'id',
            ])    */ 
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TypeAbsence::class,
        ]);
    }

    
}
