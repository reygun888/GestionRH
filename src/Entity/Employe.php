<?php

namespace App\Entity;

use App\Repository\EmployeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: EmployeRepository::class)]
class Employe implements PasswordAuthenticatedUserInterface, UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $departement = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $poste = null;

    #[ORM\OneToMany(mappedBy: 'employe', targetEntity: HeuresSup::class)]
    private Collection $heuresSups;

    #[ORM\OneToMany(mappedBy: 'employe', targetEntity: Absence::class)]
    private Collection $absences;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $roles = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;



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
            $absence->setEmploye($this);
        }

        return $this;
    }

    public function removeAbsence(Absence $absence): static
    {
        if ($this->absences->removeElement($absence)) {
            // set the owning side to null (unless already changed)
            if ($absence->getEmploye() === $this) {
                $absence->setEmploye(null);
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getRoles(): array
    {
        // Convertissez la chaîne de rôles en tableau, si elle n'est pas déjà un tableau.
        return is_array($this->roles) ? $this->roles : explode(',', $this->roles);
    }

    public function setRoles(string $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getRolesAsString(): string
    {
        return implode(', ', $this->getRoles());
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }


    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getUsername(): string
    {
        return $this->email;
    }

    public function getSalt()
    {
        // vous pouvez retourner une chaîne unique pour le sel
        return null;
    }


    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
