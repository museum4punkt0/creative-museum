<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CampaignFeedbackOptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CampaignFeedbackOptionRepository::class)]
#[ApiResource]
class CampaignFeedbackOption
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Campaign::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $campaign;

    #[ORM\Column(type: 'string', length: 255)]
    private $text;

    #[ORM\OneToMany(mappedBy: 'campaignFeedbackOption', targetEntity: User::class, orphanRemoval: true)]
    private $votes;

    public function __construct()
    {
        $this->votes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCampaign(): ?Campaign
    {
        return $this->campaign;
    }

    public function setCampaign(?Campaign $campaign): self
    {
        $this->campaign = $campaign;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function addVote(User $vote): self
    {
        if (!$this->votes->contains($vote)) {
            $this->votes[] = $vote;
            $vote->setCampaignFeedbackOption($this);
        }

        return $this;
    }

    public function removeVote(User $vote): self
    {
        if ($this->votes->removeElement($vote)) {
            // set the owning side to null (unless already changed)
            if ($vote->getCampaignFeedbackOption() === $this) {
                $vote->setCampaignFeedbackOption(null);
            }
        }

        return $this;
    }
}
