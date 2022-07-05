<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\PostVoteController;
use App\Enum\BadgeType;
use App\Enum\VoteDirection;
use App\Repository\VotesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: VotesRepository::class)]
#[ApiResource(
    collectionOperations: [
        "get",
        "post" => ["security_post_denormalize" => "is_granted('ROLE_ADMIN') or object.voter == user"],
        "post_comment" => [
            "method" => "POST",
            "path" => "/posts/{id}/vote",
            "requirements" => ["id" => "\d+", "vote" => "array"],
            "controller" => PostVoteController::class,
            'normalization_context' => ['groups' => 'write:vote'],
        ],
    ],
    itemOperations: [
        "get",
        "patch" => ["security_post_denormalize" => "is_granted('ROLE_ADMIN') or (object.voter == user and previous_object.voter == user)"],
        "delete" => ["security_post_denormalize" => "is_granted('ROLE_ADMIN') or (object.voter == user and previous_object.voter == user)"]
    ],
)]
class Votes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'votedirection')]
    #[Groups(["write:vote"])]
    private VoteDirection $direction;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["write:vote"])]
    private $voter;

    #[ORM\ManyToOne(targetEntity: Post::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["write:vote"])]
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
