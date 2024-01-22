<?php

namespace App\Entity;

use App\Repository\AbsenceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AbsenceRepository::class)]
class Absence
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateDebutAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateFinAt = null;

    #[ORM\Column(length: 255)]
    private ?string $statut = null;

    #[ORM\ManyToOne(inversedBy: 'absences')]
    private ?TypeAbsence $typeAbsence = null;

    #[ORM\ManyToOne(inversedBy: 'absences')]
    private ?Employe $employe = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebutAt(): ?\DateTimeImmutable
    {
        return $this->dateDebutAt;
    }

    public function setDateDebutAt(\DateTimeImmutable $dateDebutAt): static
    {
        $this->dateDebutAt = $dateDebutAt;

        return $this;
    }

    public function getDateFinAt(): ?\DateTimeImmutable
    {
        return $this->dateFinAt;
    }

    public function setDateFinAt(\DateTimeImmutable $dateFinAt): static
    {
        $this->dateFinAt = $dateFinAt;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getTypeAbsence(): ?TypeAbsence
    {
        return $this->typeAbsence;
    }

    public function setTypeAbsence(?TypeAbsence $typeAbsence): static
    {
        $this->typeAbsence = $typeAbsence;

        return $this;
    }

    public function getEmploye(): ?Employe
    {
        return $this->employe;
    }

    public function setEmploye(?Employe $employe): static
    {
        $this->employe = $employe;

        return $this;
    }
}
