<?php

namespace App\Entity;

use App\Repository\StatsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: StatsRepository::class)]
class Stats
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $subscriptions = null;

    #[ORM\Column(nullable: true)]
    private ?int $monthly_searches = null;

    #[ORM\Column(nullable: true)]
    private ?int $views = null;

    #[ORM\Column(nullable: true)]
    private ?int $videos_count = null;

    #[ORM\Column(nullable: true)]
    private ?int $premium_videos_count = null;

    #[ORM\Column(nullable: true)]
    private ?int $white_label_video_count = null;

    #[ORM\Column(nullable: true)]
    private ?int $stats_rank = null;

    #[ORM\Column(nullable: true)]
    private ?int $rank_premium = null;

    #[ORM\Column(nullable: true)]
    private ?int $rankwl = null;

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

    public function getSubscriptions(): ?int
    {
        return $this->subscriptions;
    }

    public function setSubscriptions(?int $subscriptions): self
    {
        $this->subscriptions = $subscriptions;

        return $this;
    }

    public function getMonthlySearches(): ?int
    {
        return $this->monthly_searches;
    }

    public function setMonthlySearches(?int $monthly_searches): self
    {
        $this->monthly_searches = $monthly_searches;

        return $this;
    }

    public function getViews(): ?int
    {
        return $this->views;
    }

    public function setViews(?int $views): self
    {
        $this->views = $views;

        return $this;
    }

    public function getVideosCount(): ?int
    {
        return $this->videos_count;
    }

    public function setVideosCount(?int $videos_count): self
    {
        $this->videos_count = $videos_count;

        return $this;
    }

    public function getPremiumVideosCount(): ?int
    {
        return $this->premium_videos_count;
    }

    public function setPremiumVideosCount(?int $premium_videos_count): self
    {
        $this->premium_videos_count = $premium_videos_count;

        return $this;
    }

    public function getWhiteLabelVideoCount(): ?int
    {
        return $this->white_label_video_count;
    }

    public function setWhiteLabelVideoCount(?int $white_label_video_count): self
    {
        $this->white_label_video_count = $white_label_video_count;

        return $this;
    }

    public function getRank(): ?int
    {
        return $this->stats_rank;
    }

    public function setRank(?int $stats_rank): self
    {
        $this->stats_rank = $stats_rank;

        return $this;
    }

    public function getRankPremium(): ?int
    {
        return $this->rank_premium;
    }

    public function setRankPremium(?int $rank_premium): self
    {
        $this->rank_premium = $rank_premium;

        return $this;
    }

    public function getRankwl(): ?int
    {
        return $this->rankwl;
    }

    public function setRankwl(?int $rankwl): self
    {
        $this->rankwl = $rankwl;

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
