<?php

namespace App\Form;

use App\Entity\Employe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangeMdpType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("ancienMDP", PasswordType::class, [
                "label" => "Ancien mot de passe",
                "mapped" => false,
                "data" => $options['ancienMDP'],
                "label_attr" => ["class" => "d-block mb-2"],
                ],
            )
            ->add("nouveauMDP", RepeatedType::class, [
                "type" => PasswordType::class,
                "invalid_message" => "Les champs du nouveau mot de passe doivent correspondre",
                "first_options" => [
                    "label" => "Nouveau mot de passe",
                    "label_attr" => ["class" => "d-block mt-2"],
                    "attr" => [
                    "class"=> "my-3"
                ],
                ],
                
                "second_options" => [
                    "label" => "Confirmez votre mot de passe",
                    "label_attr" => ["class" => "d-block mb-2"],
                    "attr" => [
                        "class"=> "form"
                    ]
                ],

                "mapped" => false, // Ne pas mapper ce champ à l'entité
            ])
            ->add("submit", SubmitType::class, [
                "label" => "Envoyer",
                "attr" => [
                    "class"=> "btn mt-3"
            ]
        ]);
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employe::class,
            "ancienMDP" => null,
        ]);
    }
}
