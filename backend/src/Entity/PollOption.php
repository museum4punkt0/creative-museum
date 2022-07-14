<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PollOptionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PollOptionRepository::class)]
#[ApiResource(
    collectionOperations: []
)]
class PollOption
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["read:post"])]
    private $id;

    /**
     * @MaxDepth(3)
     */
    #[ORM\ManyToOne(targetEntity: Post::class, inversedBy: 'pollOptions')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["write:post"])]
    private $post;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["write:post", "read:post"])]
    #[Assert\NotNull]
    #[Assert\Length(max: 100)]
    private $title;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }
}
