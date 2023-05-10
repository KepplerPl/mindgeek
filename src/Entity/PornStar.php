<?php

namespace App\Entity;

use App\Repository\PornStarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: PornStarRepository::class)]
#[UniqueEntity('external_id')]
class PornStar
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Ignore]
    private ?int $id = null;

    #[ORM\Column(name: 'external_id', unique: true)]
    private ?int $external_id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $license = null;

    #[ORM\Column(nullable: true)]
    private ?int $wl_status = null;

    #[ORM\Column(nullable: true)]
    private array $aliases = [];

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $link = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Attributes $attributes = null;

    #[ORM\Column]
    #[Ignore]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column]
    #[Ignore]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\OneToMany(mappedBy: 'pornStar', targetEntity: Thumbnail::class,cascade: ['persist', 'remove'])]
    private Collection $thumbnail;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTimeImmutable());
        if ($this->getUpdatedAt() == null) {
            $this->setUpdatedAt(new \DateTimeImmutable());
        }
        $this->thumbnail = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExternalId(): ?int
    {
        return $this->external_id;
    }

    public function setExternalId(int $external_id): self
    {
        $this->external_id = $external_id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLicense(): ?string
    {
        return $this->license;
    }

    public function setLicense(?string $license): self
    {
        $this->license = $license;

        return $this;
    }

    public function getWlStatus(): ?int
    {
        return $this->wl_status;
    }

    public function setWlStatus(?int $wl_status): self
    {
        $this->wl_status = $wl_status;

        return $this;
    }

    public function getAliases(): array
    {
        return $this->aliases;
    }

    public function setAliases(?array $aliases): self
    {
        $this->aliases = $aliases;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getAttributes(): ?Attributes
    {
        return $this->attributes;
    }

    public function setAttributes(?Attributes $attributes): self
    {
        $this->attributes = $attributes;

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return Collection<int, Thumbnail>
     */
    public function getThumbnail(): Collection
    {
        return $this->thumbnail;
    }

    public function addThumbnail(Thumbnail $thumbnail): self
    {
        if (!$this->thumbnail->contains($thumbnail)) {
            $this->thumbnail->add($thumbnail);
            $thumbnail->setPornStar($this);
        }

        return $this;
    }

    public function removeThumbnail(Thumbnail $thumbnail): self
    {
        if ($this->thumbnail->removeElement($thumbnail)) {
            // set the owning side to null (unless already changed)
            if ($thumbnail->getPornStar() === $this) {
                $thumbnail->setPornStar(null);
            }
        }

        return $this;
    }
}
