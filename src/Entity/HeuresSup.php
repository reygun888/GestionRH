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

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nombre_heures = null;

    #[ORM\ManyToOne(inversedBy: 'heuresSups')]
    private ?Employe $employe = null;

    #[ORM\Column(type: Types::TIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $heureDepart = null;

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

    public function getNombreHeures(): ?string
    {
        return $this->nombre_heures;
    }

    public function setNombreHeures(?string $nombre_heures): self
    {
        $this->nombre_heures = $nombre_heures;

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

    public function getHeureDepart(): ?\DateTimeImmutable
    {
        return $this->heureDepart;
    }

    public function setHeureDepart(?\DateTimeImmutable $heureDepart): static
    {
        $this->heureDepart = $heureDepart;

        return $this;
    }
}
