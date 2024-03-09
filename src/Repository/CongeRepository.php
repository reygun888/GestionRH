<?php

namespace App\Repository;

use App\Entity\Conge;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Conge>
 *
 * @method Conge|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conge|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conge[]    findAll()
 * @method Conge[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CongeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conge::class);
    }

    public function findCongeForMonth(int $year, int $month): array
{
    // Créer les dates de début et de fin du mois spécifié
    $startDate = new \DateTime("$year-$month-01 00:00:00");
    $endDate = (clone $startDate)->modify('last day of this month')->setTime(23, 59, 59);

    return $this->createQueryBuilder('b')
        ->andWhere('b.dateDebutAt BETWEEN :startDate AND :endDate')
        ->orWhere('b.dateFinAt BETWEEN :startDate AND :endDate')
        ->setParameter('startDate', $startDate)
        ->setParameter('endDate', $endDate)
        ->getQuery()
        ->getResult();
}

public function isDuplicateConge(Conge $conge): bool
{
    $qb = $this->createQueryBuilder('a')
        ->andWhere('a.employe = :employe')
        ->setParameter('employe', $conge->getEmploye())
        ->andWhere(':dateDebut BETWEEN a.dateDebutAt AND a.dateFinAt')
        ->andWhere(':dateFin BETWEEN a.dateDebutAt AND a.dateFinAt')
        ->setParameter('dateDebut', $conge->getDateDebutAt())
        ->setParameter('dateFin', $conge->getDateFinAt())
        ->getQuery();

    $result = $qb->getResult();

    return count($result) > 0;
}
}