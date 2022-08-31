<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PollOptionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PollOptionRepository::class)]
#[ApiResource(
    collectionOperations: [],
    itemOperations: ['get']
)]
class PollOption
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['post:read'])]
    private $id;

    /**
     * @MaxDepth(3)
     */
    #[ORM\ManyToOne(targetEntity: Post::class, inversedBy: 'pollOptions')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['post:write'])]
    private $post;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['post:write', 'post:read'])]
    #[Assert\NotNull]
    #[Assert\Length(min: 1, max: 100)]
    private $title;

    #[Groups(['post:read'])]
    private $votes = 0;

    #[Groups(['post:read'])]
    private $myChoice = false;

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


    public function getVotes(): int
    {
        return $this->votes;
    }

    public function setVotes(int $votes): self
    {
        $this->votes = $votes;

        return $this;
    }

    public function getMyChoice(): bool
    {
        return $this->myChoice;
    }

    public function setMyChoice(bool $myChoice): self
    {
        $this->myChoice = $myChoice;

        return $this;
    }
}
