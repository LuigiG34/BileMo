<?php

namespace App\Entity;

use App\Model\TimestampInterface;
use App\Repository\ClientRepository;
use App\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client implements TimestampInterface
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['getClients', 'getClientDetails'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['getClients', 'getClientDetails'])]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Groups(['getClients', 'getClientDetails'])]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    #[Groups(['getClientDetails'])]
    private ?string $email = null;

    #[ORM\Column(length: 12)]
    #[Groups(['getClientDetails'])]
    private ?string $phone = null;

    #[ORM\ManyToOne(inversedBy: 'clients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
