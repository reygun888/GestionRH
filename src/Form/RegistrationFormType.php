<?php

namespace App\Form;

use App\Entity\Employe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("nom")
            ->add("prenom")
            ->add('email')
            ->add('password', RepeatedType::class, [
                "type" => PasswordType::class,
                'mapped' => false,
                "first_options" => [
                    "label" => false,
                    "attr" => [
                        "placeholder" => "Entrez votre mot de passe",
                    ]],
                "second_options" => [
                    "label" => false,
                    "attr" => [
                        "placeholder" => "Confirmez votre mot de passe"
                    ]
                    ],
                "constraints" => [
                    new NotNull([
                        "message" => "Veuillez entrer un mot de passe"
                    ]),
                    new Length([
                        "min" => 6,
                        "minMessage" => "Votre mot de passe doit contenir au moins {{limit}} carractères",
                        "max" => 255,
                        "maxMessage" => "Votre mot de passe ne peut pas contenir plus de {{limit}} carractères",
                    ])
                ]
                    ])
            ->add("submit",SubmitType::class,[
                "label" => "Confirmer l'inscription"
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employe::class,
        ]);
    }
}