<?php

namespace App\Entity;

use App\Repository\AttributesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: AttributesRepository::class)]
class Attributes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $hair_color = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $ethnicity = null;

    #[ORM\Column(nullable: true)]
    private ?bool $tattoos = null;

    #[ORM\Column]
    private ?bool $piercings = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $breast_size = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $breast_type = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $gender = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $orientation = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $age = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Stats $stats = null;

    #[ORM\Column]
    #[Ignore]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    #[Ignore]
    private ?\DateTimeImmutable $updated_at = null;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTimeImmutable());
        if ($this->getUpdatedAt() == null) {
            $this->setUpdatedAt(new \DateTimeImmutable());
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHairColor(): ?string
    {
        return $this->hair_color;
    }

    public function setHairColor(?string $hair_color): self
    {
        $this->hair_color = $hair_color;

        return $this;
    }

    public function getEthnicity(): ?string
    {
        return $this->ethnicity;
    }

    public function setEthnicity(?string $ethnicity): self
    {
        $this->ethnicity = $ethnicity;

        return $this;
    }

    public function isTattoos(): ?bool
    {
        return $this->tattoos;
    }

    public function setTattoos(?bool $tattoos): self
    {
        $this->tattoos = $tattoos;

        return $this;
    }

    public function isPiercings(): ?bool
    {
        return $this->piercings;
    }

    public function setPiercings(bool $piercings): self
    {
        $this->piercings = $piercings;

        return $this;
    }

    public function getBreastSize(): ?int
    {
        return $this->breast_size;
    }

    public function setBreastSize(?int $breast_size): self
    {
        $this->breast_size = $breast_size;

        return $this;
    }

    public function getBreastType(): ?string
    {
        return $this->breast_type;
    }

    public function setBreastType(?string $breast_type): self
    {
        $this->breast_type = $breast_type;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getOrientation(): ?string
    {
        return $this->orientation;
    }

    public function setOrientation(?string $orientation): self
    {
        $this->orientation = $orientation;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getStats(): ?Stats
    {
        return $this->stats;
    }

    public function setStats(?Stats $stats): self
    {
        $this->stats = $stats;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
