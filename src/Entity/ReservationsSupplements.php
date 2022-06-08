<?php

namespace App\Entity;

use App\Repository\ReservationsSupplementsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationsSupplementsRepository::class)]
class ReservationsSupplements
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    private $reservationsId;

    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    private $supplementsId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReservationsId(): ?int
    {
        return $this->reservationsId;
    }

    public function setReservationsId(int $reservationsId): self
    {
        $this->reservationsId = $reservationsId;

        return $this;
    }

    public function getSupplementsId(): ?int
    {
        return $this->supplementsId;
    }

    public function setSupplementsId(int $supplementsId): self
    {
        $this->supplementsId = $supplementsId;

        return $this;
    }
}
