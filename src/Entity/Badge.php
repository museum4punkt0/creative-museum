<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Enum\BadgeType;
use App\Enum\PostType;
use App\Repository\BadgeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BadgeRepository::class)]
#[ApiResource]
class Badge
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $threshold;

    #[ORM\Column(type: 'badgetype')]
    private BadgeType $type;

    #[ORM\Column(type: 'posttype')]
    private PostType $postType;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'string', length: 255)]
    private $description;

    #[ORM\ManyToOne(targetEntity: Campaign::class, inversedBy: 'badges')]
    #[ORM\JoinColumn(nullable: false)]
    private $campaign;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getThreshold(): ?int
    {
        return $this->threshold;
    }

    public function setThreshold(int $threshold): self
    {
        $this->threshold = $threshold;

        return $this;
    }

    /**
     * @return BadgeType
     */
    public function getType(): BadgeType
    {
        return $this->type;
    }

    /**
     * @param BadgeType $type
     */
    public function setType(BadgeType $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return PostType
     */
    public function getPostType(): PostType
    {
        return $this->postType;
    }

    /**
     * @param PostType $postType
     */
    public function setPostType(PostType $postType): self
    {
        $this->postType = $postType;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
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
}
