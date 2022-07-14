<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CampaignFeedbackOptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: CampaignFeedbackOptionRepository::class)]
#[ApiResource(
    attributes: [
        "security" => "is_granted('ROLE_ADMIN')"
    ],
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
    #[Assert\Length(max: 25)]
    private $text;

    #[ORM\OneToMany(mappedBy: 'selection', targetEntity: PostFeedback::class)]
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
     * @return Collection<int, PostFeedback>
     */
    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function addVote(PostFeedback $vote): self
    {
        if (!$this->votes->contains($vote)) {
            $this->votes[] = $vote;
            $vote->setSelection($this);
        }

        return $this;
    }

    public function removeVote(PostFeedback $vote): self
    {
        if ($this->votes->removeElement($vote)) {
            // set the owning side to null (unless already changed)
            if ($vote->getSelection() === $this) {
                $vote->setSelection(null);
            }
        }

        return $this;
    }
}
