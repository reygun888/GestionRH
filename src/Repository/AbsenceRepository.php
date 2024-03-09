<?php

namespace App\Repository;

use App\Entity\Absence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Absence>
 *
 * @method Absence|null find($id, $lockMode = null, $lockVersion = null)
 * @method Absence|null findOneBy(array $criteria, array $orderBy = null)
 * @method Absence[]    findAll()
 * @method Absence[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbsenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Absence::class);
    }

    public function findAbsencesForMonth(int $year, int $month): array
{
    // Créer les dates de début et de fin du mois spécifié
    $startDate = new \DateTime("$year-$month-01 00:00:00");
    $endDate = (clone $startDate)->modify('last day of this month')->setTime(23, 59, 59);

    return $this->createQueryBuilder('a')
        ->andWhere('a.dateDebutAt BETWEEN :startDate AND :endDate')
        ->orWhere('a.dateFinAt BETWEEN :startDate AND :endDate')
        ->setParameter('startDate', $startDate)
        ->setParameter('endDate', $endDate)
        ->getQuery()
        ->getResult();
}

public function isDuplicateAbsence(Absence $absence): bool
{
    $qb = $this->createQueryBuilder('a')
        ->andWhere('a.employe = :employe')
        ->setParameter('employe', $absence->getEmploye())
        ->andWhere(':dateDebut BETWEEN a.dateDebutAt AND a.dateFinAt')
        ->andWhere(':dateFin BETWEEN a.dateDebutAt AND a.dateFinAt')
        ->setParameter('dateDebut', $absence->getDateDebutAt())
        ->setParameter('dateFin', $absence->getDateFinAt())
        ->getQuery();

    $result = $qb->getResult();

    return count($result) > 0;
}

}