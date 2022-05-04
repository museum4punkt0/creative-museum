<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Enum\BadgeType;
use App\Repository\VotesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VotesRepository::class)]
#[ApiResource]
class Votes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'votedirection')]
    private VoteDirection $direction;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $voter;

    #[ORM\ManyToOne(targetEntity: Post::class)]
    #[ORM\JoinColumn(nullable: false)]
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
