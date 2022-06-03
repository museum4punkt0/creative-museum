<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use App\Repository\CampaignRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CampaignRepository::class)]
#[ApiResource(
    attributes: [
        'filters' => ['campaign.date_filter'],
    ],
    normalizationContext: ['groups' => ['campaign:read']],
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
class Campaign
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["campaign:read"])]
    private $id;

    #[ORM\Column(type: 'boolean')]
    #[Groups(["campaign:read", "campaign:write"])]
    private $active;

    #[ORM\Column(type: 'datetime')]
    #[Groups(["campaign:read"])]
    private $created;

    #[ORM\Column(type: 'datetime')]
    #[Groups(["campaign:read", "campaign:write"])]
    private $start;

    #[ORM\Column(type: 'datetime')]
    #[Groups(["campaign:read", "campaign:write"])]
    private $stop;

    #[ORM\Column(type: 'datetime')]
    #[Groups(["campaign:read"])]
    private $updatedAt;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["campaign:read", "campaign:write"])]
    private $title;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["campaign:read", "campaign:write"])]
    private $shortDescription;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["campaign:read", "campaign:write"])]
    private $description;

    #[ORM\OneToMany(mappedBy: 'campaign', targetEntity: Award::class, orphanRemoval: true)]
    #[Groups(["campaign:read"])]
    private $awards;

    #[ORM\OneToMany(mappedBy: 'campaign', targetEntity: Badge::class, orphanRemoval: true)]
    #[Groups(["campaign:read"])]
    private $badges;

    #[ORM\OneToMany(mappedBy: 'campaign', targetEntity: Partner::class)]
    #[Groups(["campaign:read"])]
    private $partners;

    public function __construct()
    {
        $this->badges = new ArrayCollection();
        $this->awards = new ArrayCollection();
        $this->partners = new ArrayCollection();
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

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

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

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

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
}
