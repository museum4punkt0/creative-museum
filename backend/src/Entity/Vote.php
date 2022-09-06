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
use App\Controller\PostVoteController;
use App\Enum\VoteDirection;
use App\Repository\VoteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @\App\Validator\Constraints\CampaignInactive
 */
#[ORM\Entity(repositoryClass: VoteRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => [
            'normalization_context' => ['groups' => 'vote:read'],
        ],
        'post' => [
            'security_post_denormalize' => "is_granted('ROLE_ADMIN') or object.voter == user",
            'controller' => PostVoteController::class,
            'normalization_context' => ['groups' => 'vote:write'],
        ],
    ],
    itemOperations: [
        'get',
        'patch' => ['security_post_denormalize' => "is_granted('ROLE_ADMIN') or (object.voter == user and previous_object.voter == user)"],
        'delete' => ['security_post_denormalize' => "is_granted('ROLE_ADMIN') or (object.voter == user and previous_object.voter == user)"],
    ],
)]
#[ApiFilter(SearchFilter::class, properties: ['voter' => 'exact', 'post' => 'exact'])]
class Vote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'votedirection')]
    #[Groups(['vote:write', 'vote:read', 'post:read', 'playlist:read'])]
    #[ORM\JoinColumn(nullable: false)]
    private VoteDirection $direction;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[Groups(['vote:write', 'vote:read'])]
    #[ORM\JoinColumn(nullable: false)]
    public $voter;

    #[ORM\ManyToOne(targetEntity: Post::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['vote:read'])]
    private $post;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDirection(): ?VoteDirection
    {
        return $this->direction;
    }

    public function setDirection(VoteDirection $direction): self
    {
        $this->direction = $direction;

        return $this;
    }

    public function getVoter(): ?User
    {
        return $this->voter;
    }

    public function setVoter(?User $voter): self
    {
        $this->voter = $voter;

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
}
