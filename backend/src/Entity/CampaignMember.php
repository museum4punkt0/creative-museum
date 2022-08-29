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
        'get' => ['security_post_denormalize' => "is_granted('ROLE_ADMIN') or object.user == user"],
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
    #[Groups(['read:me'])]
    private $campaign;

    #[ORM\Column(type: 'integer')]
    #[Groups(['read:me'])]
    private $score = 0;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'memberships')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups(['read:me'])]
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
}
