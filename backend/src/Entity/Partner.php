<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PartnerRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PartnerRepository::class)]
#[ApiResource(
    collectionOperations: [
        "get",
        "post" => ["security_post_denormalize" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get",
        "delete" => ["security_post_denormalize" => "is_granted('ROLE_ADMIN')"]
    ],
)]
class Partner
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["campaigns:read"])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["campaigns:read"])]
    private $title;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["campaigns:read"])]
    private $url;

    #[ORM\ManyToOne(targetEntity: Campaign::class, inversedBy: 'partners')]
    private $campaign;

    #[ORM\OneToOne(targetEntity: MediaObject::class, cascade: ['persist', 'remove'])]
    private $logo;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

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

    public function getLogo(): ?MediaObject
    {
        return $this->logo;
    }

    public function setLogo(?MediaObject $logo): self
    {
        $this->logo = $logo;

        return $this;
    }
}
