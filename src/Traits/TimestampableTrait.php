<?php

namespace App\Traits;

use App\Model\TimestampInterface;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;
use Symfony\Component\Serializer\Annotation\Groups;

trait TimestampableTrait
{
    #[ORM\Column(options: ['default' => 'CURRENT_TIMESTAMP'])]
    #[Groups(['getClientDetails', 'getProductDetails'])]
    private ?\DateTimeImmutable $createdAt;

    #[ORM\Column(nullable: true)]
    #[Groups(['getClientDetails', 'getProductDetails'])]
    private ?\DateTimeImmutable $updatedAt;

    public function setCreatedAt(DateTimeImmutable $date): TimestampInterface
    {
        $this->createdAt = $date;
        return $this;
    }

    public function setUpdatedAt(DateTimeImmutable $date): TimestampInterface
    {
        $this->updatedAt = $date;
        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }
}