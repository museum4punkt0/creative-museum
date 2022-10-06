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
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Enum\BadgeType;
use App\Enum\PostType;
use App\Repository\BadgeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BadgeRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => ['normalization_context' => ['groups' => ['badge:read']]],
        'post' => ['security_post_denormalize' => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        'get',
        'patch' => ['security_post_denormalize' => "is_granted('ROLE_ADMIN')"],
        'delete' => ['security_post_denormalize' => "is_granted('ROLE_ADMIN')"],
    ],
)]
#[ApiFilter(
    SearchFilter::class,
    properties: [
        'campaign' => 'exact'
    ]
)]
#[ApiFilter(
    OrderFilter::class,
    properties: ['threshold', 'campaign.start'],
    arguments: ['orderParameterName' => 'order']
)]
class Badge
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['user:me:read', 'badge:read', 'campaigns:read'])]
    private $id;

    #[ORM\Column(type: 'integer')]
    #[Groups(['user:me:read', 'badge:read', 'campaigns:read', 'campaign:write'])]
    private $threshold;

    #[ORM\Column(type: 'badgetype')]
    #[Groups(['user:me:read', 'badge:read', 'campaigns:read', 'campaign:write'])]
    private BadgeType $type;

    #[ORM\Column(type: 'posttype')]
    #[Groups(['badge:read', 'campaigns:read', 'campaign:write'])]
    private PostType $postType = PostType::TEXT;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['user:me:read', 'users:read', 'badge:read', 'campaigns:read', 'notifications:read', 'campaign:write'])]
    private $title;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['user:me:read', 'badge:read', 'campaigns:read', 'campaign:write'])]
    private $description;

    #[ORM\ManyToOne(targetEntity: Campaign::class, inversedBy: 'badges')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['user:me:read', 'badge:read'])]
    private $campaign;

    #[ORM\OneToOne(targetEntity: MediaObject::class, cascade: ['persist','remove'], orphanRemoval: true)]
    #[Groups(['user:me:read', 'badge:read', 'campaigns:read', 'notifications:read', 'campaign:write'])]
    private $picture;

    #[ORM\Column(type: 'text')]
    #[Groups(['user:me:read', 'badge:read', 'campaigns:read', 'campaign:write'])]
    private $shortDescription;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['user:me:read', 'users:read', 'badge:read', 'campaigns:read', 'notifications:read', 'campaign:write'])]
    private $link;

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
    public function getType(): ?BadgeType
    {
        return $this->type;
    }

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

    public function getPicture(): ?MediaObject
    {
        return $this->picture;
    }

    public function setPicture(?MediaObject $picture): self
    {
        $this->picture = $picture;

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

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }
}
