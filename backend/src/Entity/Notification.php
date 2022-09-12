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
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\NotificationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: NotificationRepository::class)]
#[ApiFilter(SearchFilter::class, properties: ['silent' => 'exact', 'viewed' => 'exact', 'campaign' => 'exact', 'receiver' => 'exact'])]
#[ApiResource(
    order: ["created" => "DESC"],
    collectionOperations: [
        'get',
    ],
    normalizationContext: ['groups' => ['notifications:read']],
    denormalizationContext: ['groups' => ['notification:write']],
    itemOperations: [
        'get',
        'delete' => ['security_post_denormalize' => "is_granted('ROLE_ADMIN') or (object.receiver == user and previous_object.receiver == user)"],
        'patch' => ['normalization_context' => ['groups' => ['patch']]],
    ],
)]
#[ORM\HasLifecycleCallbacks]
class Notification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['notifications:read'])]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['notifications:read', 'notification:write'])]
    private $receiver;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['notifications:read', 'notification:write'])]
    private $text;

    #[ORM\ManyToOne(targetEntity: Post::class)]
    #[Groups(['notifications:read', 'notification:write'])]
    private $post;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['notifications:read', 'notification:write'])]
    private $silent = false;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['patch', 'notifications:read', 'notification:write'])]
    private $viewed = false;

    #[ORM\ManyToOne(targetEntity: Campaign::class)]
    #[Groups(['notifications:read', 'notification:write'])]
    private $campaign;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups(['notifications:read', 'notification:write'])]
    private $scorePoints;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['notifications:read', 'notification:write'])]
    private $created;

    #[ORM\ManyToOne(targetEntity: Award::class)]
    #[Groups(['notifications:read', 'notification:write'])]
    private $award;

    #[ORM\ManyToOne(targetEntity: Badge::class)]
    #[Groups(['notifications:read', 'notification:write'])]
    private $badge;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[Groups(['notifications:read'])]
    private $awardGiver;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[Groups(['notifications:read'])]
    private $awardWinner;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReceiver(): ?User
    {
        return $this->receiver;
    }

    public function setReceiver(?User $receiver): self
    {
        $this->receiver = $receiver;

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

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getSilent(): ?bool
    {
        return $this->silent;
    }

    public function setSilent(bool $silent): self
    {
        $this->silent = $silent;

        return $this;
    }

    public function getViewed(): ?bool
    {
        return $this->viewed;
    }

    public function setViewed(bool $viewed): self
    {
        $this->viewed = $viewed;

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

    public function getScorePoints(): ?int
    {
        return $this->scorePoints;
    }

    public function setScorePoints(?int $scorePoints): self
    {
        $this->scorePoints = $scorePoints;

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

    public function getAward(): ?Award
    {
        return $this->award;
    }

    public function setAward(?Award $award): self
    {
        $this->award = $award;

        return $this;
    }

    public function getBadge(): ?Badge
    {
        return $this->badge;
    }

    public function setBadge(?Badge $badge): self
    {
        $this->badge = $badge;

        return $this;
    }

    public function getAwardGiver(): ?User
    {
        return $this->awardGiver;
    }

    public function setAwardGiver(?User $awardGiver): self
    {
        $this->awardGiver = $awardGiver;

        return $this;
    }

    public function getAwardWinner(): ?User
    {
        return $this->awardWinner;
    }

    public function setAwardWinner(?User $awardWinner): self
    {
        $this->awardWinner = $awardWinner;

        return $this;
    }
}
