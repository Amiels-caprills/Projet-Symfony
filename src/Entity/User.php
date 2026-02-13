<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Email = null;

    #[ORM\Column(length: 255)]
    private ?string $Roles = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $PseudoMinecraft = null;

    #[ORM\Column(length: 255)]
    private ?string $UuidMinecraft = null;

    #[ORM\Column]
    private ?int $credits = null;

    #[ORM\Column]
    private ?\DateTime $DateInscription = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): static
    {
        $this->Email = $Email;

        return $this;
    }

    public function getRoles(): ?string
    {
        return $this->Roles;
    }

    public function setRoles(string $Roles): static
    {
        $this->Roles = $Roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getPseudoMinecraft(): ?string
    {
        return $this->PseudoMinecraft;
    }

    public function setPseudoMinecraft(string $PseudoMinecraft): static
    {
        $this->PseudoMinecraft = $PseudoMinecraft;

        return $this;
    }

    public function getUuidMinecraft(): ?string
    {
        return $this->UuidMinecraft;
    }

    public function setUuidMinecraft(string $UuidMinecraft): static
    {
        $this->UuidMinecraft = $UuidMinecraft;

        return $this;
    }

    public function getCredits(): ?int
    {
        return $this->credits;
    }

    public function setCredits(int $credits): static
    {
        $this->credits = $credits;

        return $this;
    }

    public function getDateInscription(): ?\DateTime
    {
        return $this->DateInscription;
    }

    public function setDateInscription(\DateTime $DateInscription): static
    {
        $this->DateInscription = $DateInscription;

        return $this;
    }
}
