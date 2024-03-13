<?php

namespace App\Entity;

use App\Repository\AbsenceRepository;
use Doctrine\DBAL\Types\Types;
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

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private ?bool $statut = null;

    #[ORM\ManyToOne(inversedBy: 'absences')]
    private ?Employe $employe = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $motif = null;

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

    public function getStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): static
    {
        $this->statut = $statut;

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

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(?string $motif): static
    {
        $this->motif = $motif;

        return $this;
    }
}
