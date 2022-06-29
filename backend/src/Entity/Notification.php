<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\NotificationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: NotificationRepository::class)]
#[ApiFilter(SearchFilter::class, properties: ['silent' => 'exact', 'viewed' => 'exact'])]
#[ApiResource(
    collectionOperations: [
        "get",
    ],
    itemOperations: [
        "get",
        "delete" => ["security_post_denormalize" => "is_granted('ROLE_ADMIN') or (object.receiver == user and previous_object.receiver == user)"],
        'patch' => ['normalization_context' => ['groups' => ['patch']]]
    ],
)]
class Notification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $receiver;

    #[ORM\Column(type: 'string', length: 255)]
    private $text;

    #[ORM\ManyToOne(targetEntity: Post::class)]
    private $post;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $color;

    #[ORM\Column(type: 'boolean')]
    private $silent = false;

    #[ORM\Column(type: 'boolean')]
    #[Groups(["patch"])]
    private $viewed = false;

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

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

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
}
