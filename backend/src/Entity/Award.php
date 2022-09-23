<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\AwardRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AwardRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get',
        'post' => ['security_post_denormalize' => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        'get',
        'patch' => ['security_post_denormalize' => "is_granted('ROLE_ADMIN')"],
        'delete' => ['security_post_denormalize' => "is_granted('ROLE_ADMIN')"],
    ],
    normalizationContext: ['groups' => ['awards:read']],
)]
#[ApiFilter(
    SearchFilter::class,
    properties: [
        'campaign' => 'exact',
    ]
)]
#[ApiFilter(
    BooleanFilter::class,
    properties: [
        'campaign.active'
    ]
)]
#[ApiFilter(
    OrderFilter::class,
    properties: ['price', 'campaign.start'],
    arguments: ['orderParameterName' => 'order']
)]
class Award
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['awards:read', 'campaigns:read'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['awards:read', 'campaigns:read', 'campaign:write', 'awarded:read', 'notifications:read'])]
    private $title;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['awards:read', 'campaigns:read', 'campaign:write', 'awarded:read'])]
    private $description;

    #[ORM\Column(type: 'integer')]
    #[Groups(['awards:read', 'campaigns:read', 'campaign:write', 'awarded:read'])]
    private $price;

    #[ORM\ManyToOne(targetEntity: Campaign::class, inversedBy: 'awards')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['awards:read', 'awarded:read', 'campaign:write'])]
    private $campaign;

    #[ORM\OneToOne(targetEntity: MediaObject::class, cascade: ['persist', 'remove'])]
    #[Groups(['awards:read', 'campaigns:read', 'campaign:write', 'awarded:read', 'notifications:read'])]
    private $picture;

    #[Groups(['campaigns:read', 'awards:read'])]
    private bool $taken = false;

    #[Groups(['campaigns:read', 'awards:read'])]
    private bool $available = false;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

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

    public function getPicture(): ?MediaObject
    {
        return $this->picture;
    }

    public function setPicture(?MediaObject $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function isTaken(): bool
    {
        return $this->taken;
    }

    public function setTaken(bool $taken): self
    {
        $this->taken = $taken;

        return $this;
    }

    public function isAvailable(): bool
    {
        return $this->available;
    }

    public function setAvailable(bool $available): self
    {
        $this->available = $available;
        return $this;
    }
}
