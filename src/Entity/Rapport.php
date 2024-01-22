<?php

namespace App\Entity;

use App\Repository\RapportRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RapportRepository::class)]
class Rapport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $periode = null;

    #[ORM\Column(length: 255)]
    private ?string $type_rapport = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPeriode(): ?string
    {
        return $this->periode;
    }

    public function setPeriode(string $periode): static
    {
        $this->periode = $periode;

        return $this;
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
}
