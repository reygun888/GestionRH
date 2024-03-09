<?php

namespace App\Controller\Admin;

use App\Entity\HeuresSup;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;

class HeuresSupCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return HeuresSup::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            DateField::new("date","Date"),
            TextField::new("nombre_heures", "Nombre d'heures"),
            TimeField::new("heure_depart", "Heure de depart"),
            AssociationField::new("employe","Employé")
            ->setLabel("Employé")
            ->setRequired(true)
            ->formatValue(function ($value) {
                return $value ? $value->getNom() . ' ' . $value->getPrenom() : "Employé inconnu";
            })
        ];
    }

}
