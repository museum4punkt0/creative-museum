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
use App\Repository\CampaignMemberRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CampaignMemberRepository::class)]
#[ApiFilter(SearchFilter::class, properties: ['user' => 'exact', 'campaign' => 'exact'])]
#[ApiResource(
    collectionOperations: [
        'get',
        'post' => ['security_post_denormalize' => "is_granted('ROLE_ADMIN') or object.user == user"],
    ],
    itemOperations: [
        'get',
        'delete' => ['security_post_denormalize' => "is_granted('ROLE_ADMIN')"],
    ],
)]
class CampaignMember
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Campaign::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['user:me:read', 'users:read'])]
    private $campaign;

    #[ORM\Column(type: 'integer')]
    #[Groups(['user:me:read', 'users:read','campaign:result:get'])]
    private $score = 0;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'memberships')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['campaign:result:get'])]
    private $user;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups(['user:me:read', 'users:read','campaign:result:get'])]
    private $rewardPoints = 0;

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

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getRewardPoints(): ?int
    {
        return $this->rewardPoints;
    }

    public function setRewardPoints(?int $rewardPoints): self
    {
        $this->rewardPoints = $rewardPoints;

        return $this;
    }

    public static function create(Campaign $campaign, User $user): CampaignMember {
        $member = new self();
        $member->setCampaign($campaign);
        $member->setUser($user);
        $member->setScore(0);
        $member->setRewardPoints(0);
        return $member;
    }
}
