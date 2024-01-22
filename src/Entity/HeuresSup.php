<?php

namespace App\Entity;

use App\Repository\HeuresSupRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HeuresSupRepository::class)]
class HeuresSup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column]
    private ?int $nombre_heures = null;

    #[ORM\Column(length: 255)]
    private ?string $approuve_par = null;

    #[ORM\ManyToOne(inversedBy: 'heuresSups')]
    private ?Employe $employe = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getNombreHeures(): ?int
    {
        return $this->nombre_heures;
    }

    public function setNombreHeures(int $nombre_heures): static
    {
        $this->nombre_heures = $nombre_heures;

        return $this;
    }

    public function getApprouvePar(): ?string
    {
        return $this->approuve_par;
    }

    public function setApprouvePar(string $approuve_par): static
    {
        $this->approuve_par = $approuve_par;

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
