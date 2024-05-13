<?php

namespace App\Controller\Admin;

use App\Entity\Absence;
use App\Entity\Employe;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AbsenceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Absence::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            DateTimeField::new("date_debut_at", "Date de début"),
            DateTimeField::new("date_fin_at", "Date de fin"),
            BooleanField::new("statut", "Absence justifiée ?"),
            TextareaField::new("motif", "Motif"),
            AssociationField::new("employe", "Employé")
                ->setLabel("Employé")
                ->setRequired(true)
                ->autocomplete()
                ->formatValue(function ($value) {
                    return $value ? strtoupper($value->getNom()) . ' ' . $value->getPrenom() : "Employé inconnu";
                })
        ];
    }
    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('employe', 'Employé'));
    }
}