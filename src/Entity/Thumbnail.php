<?php

namespace App\Entity;

use App\Repository\ThumbnailRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: ThumbnailRepository::class)]
class Thumbnail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $height = null;

    #[ORM\Column(nullable: true)]
    private ?int $width = null;

    #[ORM\Column(length: 20)]
    private ?string $type = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\ManyToOne(inversedBy: 'thumbnail')]
    private ?PornStar $pornStar = null;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'thumbnails')]
    private ?ThumbnailImage $tumbnail_image = null;

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

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(?int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(?int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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

    public function getPornStar(): ?PornStar
    {
        return $this->pornStar;
    }

    public function setPornStar(?PornStar $pornStar): self
    {
        $this->pornStar = $pornStar;

        return $this;
    }

    public function getTumbnailImage(): ?ThumbnailImage
    {
        return $this->tumbnail_image;
    }

    public function setTumbnailImage(?ThumbnailImage $tumbnail_image): self
    {
        $this->tumbnail_image = $tumbnail_image;

        return $this;
    }
}
