<?php

namespace App\Controller\Admin;

use App\Entity\HeuresSup;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

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
            IntegerField::new("nombre_heures", "Nombre d'heures"),
            TextField::new("approuve_par", "Approuvé par"),
            AssociationField::new("employe","Employé")
            ->setLabel("Employé")
                ->setRequired(true)
                ->formatValue(function ($value) {
                    return $value ? strtoupper($value->getNom()) . ' ' . $value->getPrenom() : "Employé inconnu";
                })

        ];
    }

}
