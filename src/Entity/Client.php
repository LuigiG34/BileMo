<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;
use League\Bundle\OAuth2ServerBundle\Entity\Client as ClientOAuth;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\OneToOne(targetEntity: "ClientOAuth", mappedBy: "client", cascade: ["persist", "remove"])]
    private $clientOAuth;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    public function getClientOAuth(): ClientOAuth
    {
        return $this->clientOAuth;
    }

    public function setClientOAuth($clientOAuth): static
    {
        $this->clientOAuth = $clientOAuth;

        return $this;
    }
}
