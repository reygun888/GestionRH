<?php

namespace App\Entity;

use App\Repository\CongeRepository;
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

    #[ORM\Column(length: 255)]
    private ?string $regles = null;

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

    public function getRegles(): ?string
    {
        return $this->regles;
    }

    public function setRegles(string $regles): static
    {
        $this->regles = $regles;

        return $this;
    }
}
