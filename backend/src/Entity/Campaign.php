<?php

declare(strict_types=1);

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\GetCampaignLeaderBoardController;
use App\Repository\CampaignRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @\App\Validator\Constraints\FeedbackOptionCount
 */
#[ORM\Entity(repositoryClass: CampaignRepository::class)]
#[ORM\Index(fields: ['active', 'notified', 'start', 'stop'], name: 'campaign_collection_index')]
#[ApiResource(
    collectionOperations: [
        'get',
        'post' => ['security_post_denormalize' => "is_granted('ROLE_ADMIN')"],
        'get_leaderboard' => [
            'method' => 'GET',
            'path' => '/campaigns/result/{campaignId}',
            'requirements' => ['id' => "\d+"],
            'controller' => GetCampaignLeaderBoardController::class,
            'normalization_context' => ['groups' => 'campaign:result:get'],
        ],
    ],
    itemOperations: [
        'get',
        'patch' => ['security_post_denormalize' => "is_granted('ROLE_ADMIN')"],
        'delete' => ['security_post_denormalize' => "is_granted('ROLE_ADMIN')"],
    ],
    attributes: [
        'filters' => ['campaign.date_filter'],
    ],
    denormalizationContext: ['groups' => ['campaign:write']],
    normalizationContext: ['groups' => ['campaigns:read']],
    order: ['id' => 'DESC'],
)]
#[ApiFilter(
    DateFilter::class,
    strategy: DateFilter::PARAMETER_BEFORE,
    properties: ['start']),
]
#[ApiFilter(
    SearchFilter::class,
    properties: [
        'published' => 'exact',
        'active' => 'exact',
    ]
)]
#[ORM\HasLifecycleCallbacks]
class Campaign
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['campaigns:read', 'post:read', 'user:me:read', 'awards:read', 'badge:read', 'playlist:read'])]
    private $id;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['campaigns:read', 'campaign:write', 'awards:read', 'post:read', 'playlist:read'])]
    private $active;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['campaigns:read', 'campaign:write'])]
    private $created;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['campaigns:read', 'campaign:write'])]
    private $start;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['campaigns:read', 'campaign:write'])]
    private $stop;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['campaigns:read', 'campaign:write'])]
    private $updatedAt;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['campaigns:read', 'campaign:write', 'post:read', 'user:me:read', 'badges:read', 'awards:read', 'playlist:read', 'notifications:read', 'users:read'])]
    private $title;

    #[ORM\Column(type: 'text')]
    #[Groups(['campaigns:read', 'campaign:write'])]
    private $shortDescription;

    #[ORM\Column(type: 'text')]
    #[Groups(['campaigns:read', 'campaign:write'])]
    private $description;

    #[ORM\OneToMany(mappedBy: 'campaign', targetEntity: Award::class, cascade: ["persist","remove"], orphanRemoval: true)]
    #[Groups(['campaigns:read', 'campaign:write'])]
    private $awards;

    #[ORM\OneToMany(mappedBy: 'campaign', targetEntity: Badge::class, cascade: ["persist","remove"], orphanRemoval: true)]
    #[Groups(['campaigns:read', 'campaign:write'])]
    private $badges;

    #[ORM\OneToMany(mappedBy: 'campaign', targetEntity: Partner::class,cascade: ["persist","remove"], orphanRemoval: true)]
    #[Groups(['campaigns:read', 'campaign:read', 'campaign:write'])]
    private $partners;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['campaigns:read', 'campaign:read', 'campaign:write', 'awards:read', 'badged:read', 'post:read', 'user:me:read', 'awarded:read', 'badge:read', 'playlist:read', 'users:read', 'notifications:read'])]
    private $color;

    #[ORM\Column(type: 'boolean')]
    private $notified = false;

    #[ORM\OneToMany(
        mappedBy: 'campaign',
        targetEntity: CampaignFeedbackOption::class,
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    #[Groups(['campaigns:read', 'campaign:write'])]
    #[Assert\Valid]
    private $feedbackOptions;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['campaigns:read', 'campaign:read', 'campaign:write', 'post:read'])]
    private $closed = false;

    #[ORM\OneToMany(mappedBy: 'campaign', targetEntity: Post::class, cascade: ['remove'])]
    private $posts;

    #[ORM\Column(type: 'boolean', options: ['default' => 0])]
    #[Groups(['campaigns:read', 'campaign:read', 'campaign:write'])]
    private $published = false;

    public function __construct()
    {
        $this->badges = new ArrayCollection();
        $this->awards = new ArrayCollection();
        $this->partners = new ArrayCollection();
        $this->feedbackOptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    #[ORM\PrePersist]
    public function setCreated(): self
    {
        $this->created = new \DateTimeImmutable();

        return $this;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getStop(): ?\DateTimeInterface
    {
        return $this->stop;
    }

    public function setStop(\DateTimeInterface $stop): self
    {
        $this->stop = $stop;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setUpdatedAt(): self
    {
        $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

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

    /**
     * @return Collection<int, Award>
     */
    public function getAwards(): Collection
    {
        return $this->awards;
    }

    public function addAward(Award $award): self
    {
        if (!$this->awards->contains($award)) {
            $this->awards[] = $award;
            $award->setCampaign($this);
        }

        return $this;
    }

    public function removeAward(Award $award): self
    {
        if ($this->awards->removeElement($award)) {
            // set the owning side to null (unless already changed)
            if ($award->getCampaign() === $this) {
                $award->setCampaign(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Badge>
     */
    public function getBadges(): Collection
    {
        return $this->badges;
    }

    public function addBadge(Badge $badge): self
    {
        if (!$this->badges->contains($badge)) {
            $this->badges[] = $badge;
            $badge->setCampaign($this);
        }

        return $this;
    }

    public function removeBadge(Badge $badge): self
    {
        if ($this->badges->removeElement($badge)) {
            // set the owning side to null (unless already changed)
            if ($badge->getCampaign() === $this) {
                $badge->setCampaign(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Partner>
     */
    public function getPartners(): Collection
    {
        return $this->partners;
    }

    public function addPartner(Partner $partner): self
    {
        if (!$this->partners->contains($partner)) {
            $this->partners[] = $partner;
            $partner->setCampaign($this);
        }

        return $this;
    }

    public function removePartner(Partner $partner): self
    {
        if ($this->partners->removeElement($partner)) {
            // set the owning side to null (unless already changed)
            if ($partner->getCampaign() === $this) {
                $partner->setCampaign(null);
            }
        }

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getNotified(): ?bool
    {
        return $this->notified;
    }

    public function setNotified(bool $notified): self
    {
        $this->notified = $notified;

        return $this;
    }

    /**
     * @return Collection<int, CampaignFeedbackOption>
     */
    public function getFeedbackOptions(): Collection
    {
        return $this->feedbackOptions;
    }

    public function addFeedbackOption(CampaignFeedbackOption $feedbackOption): self
    {
        if (!$this->feedbackOptions->contains($feedbackOption)) {
            $this->feedbackOptions[] = $feedbackOption;
            $feedbackOption->setCampaign($this);
        }

        return $this;
    }

    public function removeFeedbackOption(CampaignFeedbackOption $feedbackOption): self
    {
        if ($this->feedbackOptions->removeElement($feedbackOption)) {
            // set the owning side to null (unless already changed)
            if ($feedbackOption->getCampaign() === $this) {
                $feedbackOption->setCampaign(null);
            }
        }

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->created = new \DateTime('now');
        $this->updatedAt = $this->created;
    }

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->updatedAt = new \DateTime('now');
    }

    public function getClosed(): ?bool
    {
        return $this->closed;
    }

    public function setClosed(bool $closed): self
    {
        $this->closed = $closed;

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setCampaign($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getCampaign() === $this) {
                $post->setCampaign(null);
            }
        }

        return $this;
    }

    public function getPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): self
    {
        $this->published = $published;

        return $this;
    }
}
