<?php

namespace App\Entity;

use App\Repository\ThumbnailUrlRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: ThumbnailUrlRepository::class)]
#[UniqueEntity('url')]
class ThumbnailImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'url', length: 100, unique: true)]
    private ?string $url = null;

    #[ORM\OneToMany(mappedBy: 'tumbnail_image', targetEntity: Thumbnail::class)]
    #[Ignore]
    private Collection $thumbnails;

    public function __construct()
    {
        $this->thumbnails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->url;
    }

    public function setName(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return Collection<int, Thumbnail>
     */
    public function getThumbnails(): Collection
    {
        return $this->thumbnails;
    }

    public function addThumbnail(Thumbnail $thumbnail): self
    {
        if (!$this->thumbnails->contains($thumbnail)) {
            $this->thumbnails->add($thumbnail);
            $thumbnail->setTumbnailImage($this);
        }

        return $this;
    }

    public function removeThumbnail(Thumbnail $thumbnail): self
    {
        if ($this->thumbnails->removeElement($thumbnail)) {
            // set the owning side to null (unless already changed)
            if ($thumbnail->getTumbnailImage() === $this) {
                $thumbnail->setTumbnailImage(null);
            }
        }

        return $this;
    }
}
