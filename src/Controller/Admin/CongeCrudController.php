<?php

namespace App\Controller\Admin;

use App\Entity\Conge;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;


class CongeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Conge::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            ChoiceField::new("typeConge", "Type de congé")
                ->setChoices([
                    "Congé payé" => "Congé payé",
                    "Congé maternité" => "Congé maternité",
                    "Congé maladie" => "Congé maladie",
                    "Congé parental" => "Congé parental",
                ]),
            DateField::new("date_debut_at", "Date de début"),
            DateField::new("date_fin_at", "Date de retour"),
            AssociationField::new("employe", "Employé")
            ->setLabel("Employé")
                ->setRequired(true)
                ->autocomplete()
                ->formatValue(function ($value) {
                    return $value ? strtoupper($value->getNom()) . ' ' . $value->getPrenom() : "Employé inconnu";
                })
        ];
    }
}
