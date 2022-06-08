<?php

namespace App\Entity;

use App\Repository\ThemesRepository;
use Doctrine\DBAL\Events;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use PhpParser\Node\Expr\Instanceof_;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity(repositoryClass: ThemesRepository::class)]
class Themes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $description;

    #[ORM\Column(type: 'string', length: 255)]
    private $duree;

    #[ORM\Column(type: 'integer')]
    private $prix;

    #[ORM\Column(type: 'integer')]
    private $ageMinimum;

    #[ORM\Column(type: 'integer')]
    private $ageMaximum;

    #[ORM\Column(type: 'integer')]
    private $nbEnfantsMinimum;

    #[ORM\Column(type: 'integer')]
    private $nbEnfantsMaximum;

    #[ORM\Column(type: 'string', length: 255)]
    private $imageIllustration;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDuree(): ?string
    {
        return $this->duree;
    }

    public function setDuree(string $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getAgeMinimum(): ?int
    {
        return $this->ageMinimum;
    }

    public function setAgeMinimum(int $ageMinimum): self
    {
        $this->ageMinimum = $ageMinimum;

        return $this;
    }

    public function getAgeMaximum(): ?int
    {
        return $this->ageMaximum;
    }

    public function setAgeMaximum(int $ageMaximum): self
    {
        $this->ageMaximum = $ageMaximum;

        return $this;
    }

    public function getNbEnfantsMinimum(): ?int
    {
        return $this->nbEnfantsMinimum;
    }

    public function setNbEnfantsMinimum(int $nbEnfantsMinimum): self
    {
        $this->nbEnfantsMinimum = $nbEnfantsMinimum;

        return $this;
    }

    public function getNbEnfantsMaximum(): ?int
    {
        return $this->nbEnfantsMaximum;
    }

    public function setNbEnfantsMaximum(int $nbEnfantsMaximum): self
    {
        $this->nbEnfantsMaximum = $nbEnfantsMaximum;

        return $this;
    }

    public function getImageIllustration(): null|string|UploadedFile
    {
        return $this->imageIllustration;
    }

    public function setImageIllustration(null|string|UploadedFile $imageIllustration): self
    {
        $this->imageIllustration = $imageIllustration;

        return $this;
    }

    // public function postLoad(LifecycleEventArgs $event):void
    // {
    //     if($event->getEntity() Instanceof Themes){
    //         $event->getEntity()->prevImage = $event->getEntity()->getImage;
    //     }
    // }

    // public function getSubscribedEvents():array
    // {
    //     return [
    //         Events::postLoad,
    //     ];
    // }

}
