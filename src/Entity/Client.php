<?php

namespace App\Entity;

use App\Model\TimestampInterface;
use App\Repository\ClientRepository;
use App\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

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
    #[Assert\NotBlank(
        message: 'The firstname should not be blank',
    )]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z\s\-]+$/",
        message: 'The firstname can only contain letters, spaces, and hyphens',
    )]
    #[Assert\Length(
        min: 1,
        max: 255,
        minMessage: 'Your firstname must be at least {{ limit }} characters long',
        maxMessage: 'Your firstname cannot be longer than {{ limit }} characters',
    )]
    #[Groups(['getClients', 'getClientDetails'])]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        message: 'The lastname should not be blank',
    )]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z\s\-]+$/",
        message: 'The lastname can only contain letters, spaces, and hyphens',
    )]
    #[Assert\Length(
        min: 1,
        max: 255,
        minMessage: 'Your lastname must be at least {{ limit }} characters long',
        maxMessage: 'Your lastname cannot be longer than {{ limit }} characters',
    )]
    #[Groups(['getClients', 'getClientDetails'])]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        message: 'The email should not be blank',
    )]
    #[Assert\Email(
        message: 'The email {{ value }} is not a valid email.',
    )]
    #[Groups(['getClientDetails'])]
    private ?string $email = null;

    #[ORM\Column(length: 12)]
    #[Assert\NotBlank(
        message: 'The phone should not be blank',
    )]
    #[Assert\Regex(
        pattern: "/^\+[1-9]\d{1,14}$/",
        message: 'The phone number should be in E.164 format',
    )]
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
