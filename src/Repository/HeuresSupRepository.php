<?php

namespace App\Repository;

use App\Entity\HeuresSup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class HeuresSupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HeuresSup::class);
    }

    public function findHeuresSupForMonth(int $year, int $month): array
    {
        // Créer les dates de début et de fin du mois spécifié
        $startDate = new \DateTime("$year-$month-01 00:00:00");
        $endDate = (clone $startDate)->modify('last day of this month')->setTime(23, 59, 59);

        return $this->createQueryBuilder('h')
            ->andWhere('h.date BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getResult();
    }
    public function isDuplicateHeureSup(HeuresSup $heuresSup): bool
    {
        $qb = $this->createQueryBuilder('h')
            ->andWhere('h.employe = :employe')
            ->setParameter('employe', $heuresSup->getEmploye())
            ->andWhere('h.date = :date')
            ->setParameter('date', $heuresSup->getDate())
            ->getQuery();
        
        $result = $qb->getResult();

        return count($result) > 0;
    }
}