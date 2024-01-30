<?php

namespace App\Entity;

use App\Repository\RapportRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RapportRepository::class)]
class Rapport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(length: 255)]
    private ?string $type_rapport = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $periodeDebutAt = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $periodeFinAt = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Employe $employe = null;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getTypeRapport(): ?string
    {
        return $this->type_rapport;
    }

    public function setTypeRapport(string $type_rapport): static
    {
        $this->type_rapport = $type_rapport;

        return $this;
    }

    public function getPeriodeDebutAt(): ?\DateTimeInterface
    {
        return $this->periodeDebutAt;
    }

    public function setPeriodeDebutAt(\DateTimeInterface $periodeDebutAt): static
    {
        $this->periodeDebutAt = $periodeDebutAt;

        return $this;
    }

    public function getPeriodeFinAt(): ?\DateTimeInterface
    {
        return $this->periodeFinAt;
    }

    public function setPeriodeFinAt(\DateTimeInterface $periodeFinAt): static
    {
        $this->periodeFinAt = $periodeFinAt;

        return $this;
    }

    public function getEmploye(): ?Employe
    {
        return $this->employe;
    }

    public function setEmploye(Employe $employe): static
    {
        $this->employe = $employe;

        return $this;
    }
}
