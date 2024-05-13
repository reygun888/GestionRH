<?php

namespace App\Controller\Admin;

use App\Entity\HeuresSup;
use App\Entity\Employe;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

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
    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('employe', 'Employé'));
    }
}