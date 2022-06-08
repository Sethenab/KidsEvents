<?php

namespace App\Entity;

use App\Repository\ReservationsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationsRepository::class)]
class Reservations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $themeId;

    #[ORM\Column(type: 'integer')]
    private $userId;

    #[ORM\Column(type: 'date')]
    private $dateReservee;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getThemeId(): ?int
    {
        return $this->themeId;
    }

    public function setThemeId(int $themeId): self
    {
        $this->themeId = $themeId;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getDateReservee(): ?\DateTimeInterface
    {
        return $this->dateReservee;
    }

    public function setDateReservee(\DateTimeInterface $dateReservee): self
    {
        $this->dateReservee = $dateReservee;

        return $this;
    }
}
