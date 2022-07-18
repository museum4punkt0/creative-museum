<?php
declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
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
#[ApiResource(
    attributes: [
        'filters' => ['campaign.date_filter'],
    ],
    normalizationContext: ['groups' => ['campaigns:read']],
    denormalizationContext: ['groups' => ['campaign:write']],
    order: ["start" => "DESC"],
    collectionOperations: [
        "get",
        "post" => ["security_post_denormalize" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get",
        "patch" => ["security_post_denormalize" => "is_granted('ROLE_ADMIN')"],
        "delete" => ["security_post_denormalize" => "is_granted('ROLE_ADMIN')"],
    ],
)]
#[ApiFilter(DateFilter::class, strategy: DateFilter::PARAMETER_BEFORE, properties: ['start'])]
#[ORM\HasLifecycleCallbacks]
class Campaign
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["campaigns:read","read:post"])]
    private $id;

    #[ORM\Column(type: 'boolean')]
    #[Groups(["campaigns:read", "campaign:write"])]
    private $active;

    #[ORM\Column(type: 'datetime')]
    #[Groups(["campaigns:read","campaign:write"])]
    private $created;

    #[ORM\Column(type: 'datetime')]
    #[Groups(["campaigns:read", "campaign:write"])]
    private $start;

    #[ORM\Column(type: 'datetime')]
    #[Groups(["campaigns:read", "campaign:write"])]
    private $stop;

    #[ORM\Column(type: 'datetime')]
    #[Groups(["campaigns:read", "campaign:write"])]
    private $updatedAt;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["campaigns:read", "campaign:write"])]
    private $title;

    #[ORM\Column(type: 'text')]
    #[Groups(["campaigns:read", "campaign:write"])]
    private $shortDescription;

    #[ORM\Column(type: 'text')]
    #[Groups(["campaigns:read", "campaign:write"])]
    private $description;

    #[ORM\OneToMany(mappedBy: 'campaign', targetEntity: Award::class, orphanRemoval: true)]
    #[Groups(["campaigns:read"])]
    private $awards;

    #[ORM\OneToMany(mappedBy: 'campaign', targetEntity: Badge::class, orphanRemoval: true)]
    #[Groups(["campaigns:read"])]
    private $badges;

    #[ORM\OneToMany(mappedBy: 'campaign', targetEntity: Partner::class)]
    #[Groups(["campaigns:read"])]
    private $partners;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(["campaigns:read", "campaign:read", "campaign:write"])]
    private $color;

    #[ORM\Column(type: 'boolean')]
    private $notified = false;

    #[ORM\OneToMany(mappedBy: 'campaign', targetEntity: CampaignFeedbackOption::class, cascade: ["persist","remove"])]
    #[Groups(["campaigns:read","campaign:write"])]
    #[Assert\Valid]
    private $feedbackOptions;

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
        $this->created = new \DateTime("now");
        $this->updatedAt = $this->created;
    }

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->updatedAt = new \DateTime("now");
    }
}
