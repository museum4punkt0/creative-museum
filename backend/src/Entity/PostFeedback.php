<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PostFeedbackRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @\App\Validator\Constraints\FeedbackGiven
 */
#[ORM\Entity(repositoryClass: PostFeedbackRepository::class)]
#[ApiResource(
    collectionOperations: [
        "get",
        "post" => ["security_post_denormalize" => "is_granted('ROLE_ADMIN') or object.author == user"],
    ],
    itemOperations: [
        "get",
        "patch" => ["security_post_denormalize" => "is_granted('ROLE_ADMIN') or (object.author == user and previous_object.author == user)"],
        "delete" => ["security_post_denormalize" => "is_granted('ROLE_ADMIN') or (object.author == user and previous_object.author == user)"]
    ],
)]
class PostFeedback
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\ManyToOne(targetEntity: Post::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $post;

    #[ORM\ManyToOne(targetEntity: CampaignFeedbackOption::class, inversedBy: 'votes')]
    #[ORM\JoinColumn(nullable: false)]
    private $selection;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getSelection(): ?CampaignFeedbackOption
    {
        return $this->selection;
    }

    public function setSelection(?CampaignFeedbackOption $selection): self
    {
        $this->selection = $selection;

        return $this;
    }
}
