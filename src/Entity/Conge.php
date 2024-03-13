<?php

namespace App\Entity;

use App\Repository\CongeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CongeRepository::class)]
class Conge
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type_conge = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable:true)]
    private ?bool $statut = null;

    #[ORM\ManyToOne(targetEntity: Employe::class, inversedBy: 'conges')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Employe $employe = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateDebutAt = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateFinAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeConge(): ?string
    {
        return $this->type_conge;
    }

    public function setTypeConge(string $type_conge): static
    {
        $this->type_conge = $type_conge;

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

    public function getDateDebutAt(): ?\DateTimeInterface
    {
        return $this->dateDebutAt;
    }

    public function setDateDebutAt(\DateTimeInterface $dateDebutAt): static
    {
        $this->dateDebutAt = $dateDebutAt;

        return $this;
    }

    public function getDateFinAt(): ?\DateTimeInterface
    {
        return $this->dateFinAt;
    }

    public function setDateFinAt(\DateTimeInterface $dateFinAt): static
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
}