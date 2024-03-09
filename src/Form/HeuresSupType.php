<?php

namespace App\Form;

use App\Entity\Employe;
use App\Entity\HeuresSup;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class HeuresSupType extends AbstractType
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
            ->add('date',DateType::class,[
                'data' => new \DateTime(),
                "label" => "Date",
                "label_attr" => ["class" => "me-2"],
                "attr" => [
                    "class"=> "mt-1 ps-2",
                    'readonly' => true,
                ],
                "data" => new \DateTime(),  // Définir la date actuelle comme date par défaut,
            ])
            ->add('heure_depart', TimeType::class, [ // Ajouter le champ heure_depart
                'label' => 'Heure de départ',
                'widget' => 'single_text',
                "label_attr" => ["class" => "me-2"],
                'attr' => [
                    'class' => 'mt-1 ps-2',
                    'placeholder' => 'HH:mm', // Facultatif : placeholder pour guider l'utilisateur
                ],
            ])
            
            ->add('nombre_heures', TextType::class, [
                "label" => "Total",
                'required' => false, // Ajoutez cette ligne
                // 'widget' => 'single_text',
                "label_attr" => ["class" => "d-none"],
                "attr" => [
                    "class" => "mt-3 ps-2",
                    "style" => "display: none",
                ],
            ]);
            // ->add('nombre_heures', TextType::class, [
            //     "mapped" => false,
            //     "label" => "Total",
            //     'required' => false, // Ajoutez cette ligne
            //     "label_attr" => ["class" => "d-none"],
            //     "attr" => [
            //         "class" => "d-none",
            //         "style" => "display: none",
            //     ],
            // ]);

            if ($isAdmin || $isManager) {
                $builder 
            ->add('employe', EntityType::class, [
                'class' => Employe::class,
                'choice_label' => 'nom', // ou tout autre champ que vous voulez afficher
                'disabled' => true, // Empêcher l'utilisateur de changer l'employé
                "label_attr" => ["class" => "me-3"],
                "attr" => [
                    "class" => "mt-3 py-1 w-50"
                ],
                'disabled' => !($isAdmin || $isManager), // Désactivé si l'utilisateur n'est pas un administrateur
            ]);
            }
            $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $formData = $event->getData();
                $form = $event->getForm();
            
                if (isset($formData['heure_depart'])) {
                    $heureDepart = new \DateTimeImmutable($formData['heure_depart']);
                    if ($heureDepart->format('H') >= 17) {
                        // Définir l'heure de début des heures supplémentaires à 17:00
                        $heureDebutHS = new \DateTimeImmutable('17:00:00');
            
                        // Calculer la différence d'heures entre l'heure de départ et l'heure de début des heures supplémentaires
                        $duree = $heureDepart->diff($heureDebutHS);
            
                        $formData['nombre_heures'] = $duree->format('%H:%I');
            
                        // Mettre à jour le champ 'nombre_heures' avec la nouvelle valeur
                        $form->get('nombre_heures')->setData($duree->format('%H:%I'));
                    }
                }
            
                // Mettre à jour les données du formulaire
                $event->setData($formData);
            });
            $builder
                ->add("submit", SubmitType::class, [
                    "label" => "Envoyer",
                    "attr" => [
                        "class" => "btn btn-primary mt-3"
                    ],
                ]);
        }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => HeuresSup::class,
        ]);
    }
}
