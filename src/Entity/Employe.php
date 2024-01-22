<?php

namespace App\Entity;

use App\Repository\EmployeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployeRepository::class)]
class Employe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $departement = null;

    #[ORM\Column(length: 255)]
    private ?string $poste = null;

    #[ORM\OneToMany(mappedBy: 'employe', targetEntity: HeuresSup::class)]
    private Collection $heuresSups;

    #[ORM\OneToMany(mappedBy: 'employeId', targetEntity: Absence::class)]
    private Collection $absences;

    #[ORM\ManyToOne(inversedBy: 'employe')]
    private ?Absence $absence = null;

    public function __construct()
    {
        $this->heuresSups = new ArrayCollection();
        $this->absences = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDepartement(): ?string
    {
        return $this->departement;
    }

    public function setDepartement(string $departement): static
    {
        $this->departement = $departement;

        return $this;
    }

    public function getPoste(): ?string
    {
        return $this->poste;
    }

    public function setPoste(string $poste): static
    {
        $this->poste = $poste;

        return $this;
    }

    /**
     * @return Collection<int, HeuresSup>
     */
    public function getHeuresSups(): Collection
    {
        return $this->heuresSups;
    }

    public function addHeuresSup(HeuresSup $heuresSup): static
    {
        if (!$this->heuresSups->contains($heuresSup)) {
            $this->heuresSups->add($heuresSup);
            $heuresSup->setEmploye($this);
        }

        return $this;
    }

    public function removeHeuresSup(HeuresSup $heuresSup): static
    {
        if ($this->heuresSups->removeElement($heuresSup)) {
            // set the owning side to null (unless already changed)
            if ($heuresSup->getEmploye() === $this) {
                $heuresSup->setEmploye(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Absence>
     */
    public function getAbsences(): Collection
    {
        return $this->absences;
    }

    public function addAbsence(Absence $absence): static
    {
        if (!$this->absences->contains($absence)) {
            $this->absences->add($absence);
            $absence->setEmployeId($this);
        }

        return $this;
    }

    public function removeAbsence(Absence $absence): static
    {
        if ($this->absences->removeElement($absence)) {
            // set the owning side to null (unless already changed)
            if ($absence->getEmployeId() === $this) {
                $absence->setEmployeId(null);
            }
        }

        return $this;
    }

    public function getAbsence(): ?Absence
    {
        return $this->absence;
    }

    public function setAbsence(?Absence $absence): static
    {
        $this->absence = $absence;

        return $this;
    }
}
